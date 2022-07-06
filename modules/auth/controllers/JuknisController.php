<?php

namespace app\modules\auth\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\{
    BadRequestHttpException,
    Controller,
    NotFoundHttpException,
    ForbiddenHttpException,
    Response,
    UploadedFile
};
use yii\filters\{
    VerbFilter,
    AccessControl
};
use app\models\danabos\{
    generals\Juknis,
    generals\JuknisItem,
    generals\JuknisRelation,
    generals\Settings,
    searches\Juknis as JuknisSearch,
    searches\JuknisItem as JuknisItemSearch,
    searches\JuknisRelation as JuknisRelationSearch,
};
use app\models\smart\{
    generals\MTahunAjaran,
    generals\PublicSekolah
};
use app\utils\{
    gdrive\GDrive,
    helper\Helper
};


use yii\widgets\ActiveForm;

class JuknisController extends Controller
{
    public $title = "Juknis";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'validate', 'handle-file', 'get-schools', 'add-juknis-item', 'get-parent-juknis', 'validate-item-juknis', 'item-delete', 'switch-item'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * @throws
     */
    public function beforeAction($action): bool
    {
        if ($action->id == 'handle-file') :
            Yii::$app->request->enableCsrfValidation = false;
        endif;
        return parent::beforeAction($action);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $searchModel    = new JuknisSearch();

            $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['or', ['status' => 1], ['status' => 2]])
                ->orderBy(['id' => SORT_DESC]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionCreate()
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $model      = new Juknis();
            $msg        = "";
            if ($model->load(Yii::$app->request->post())) :
                if ($model->schools) :
                    $modelSchool = PublicSekolah::find()->where(['in', 'sekolah_id', $model->schools])->all();
                    $school = ArrayHelper::getColumn($modelSchool, function ($data) {
                        return [
                            'id' => $data->sekolah_id,
                            'text' => $data->nama,
                        ];
                    });
                    $model->schools = json_encode($school);
                else :
                    $model->schools = NULL;
                endif;
                $model->code = $this->generateCode(6);
                if ($model->save()) :
                    $msg = Yii::t('app', "Data berhasil di tambah");
                    Yii::$app->session->setFlash('success', $msg);
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'create', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('danger', $msg);

            endif;
            $year = ArrayHelper::index(range(date('Y') - 2, date('Y') + 2), function ($data) {
                return $data;
            });
            return $this->render('create', [
                'model' => $model,
                'year' => $year,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionAddJuknisItem($code)
    {
        $model = new JuknisItem();
        $model->scenario = "item-juknis";
        $msg = "";
        if ($model->load(Yii::$app->request->post())) :
            $model->code = $this->generateCode2(6);
            if ($model->save()) :
                $include = Yii::$app->request->post('include');
                $juknisId = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
                if ($include) :
                    $modelRelation = new JuknisRelation();
                    $modelRelation->juknis_id = $juknisId;
                    $modelRelation->juknis_item_id = $model->id;
                    $modelRelation->save(false);
                endif;
                $msg = Yii::t('app', "Data berhasil di tambah");
                Yii::$app->session->setFlash('success', $msg);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'create', 'type' => null, 'msg' => $msg];
            endif;
            $err = $model->getErrors();
            $msg = $err[key($err)][0];
            Yii::$app->session->setFlash('danger', $msg);

        endif;
        return $this->renderAjax('add-juknis-item/create', ['model' => $model, 'code' => $code]);
    }

    public function actionView($code)
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $code       = Yii::$app->encryptor->decodeUrl($code);

            $model = $this->findModel($code);

            $allItemJuknisSearch    = new JuknisItemSearch();
            $allItemJuknisProvider   = $allItemJuknisSearch->search(Yii::$app->request->queryParams);
            $allItemJuknisProvider->query
                ->joinWith('juknisRelations')
                ->andWhere("juknis_item.id NOT IN (SELECT juknis_relation.juknis_item_id FROM juknis_relation WHERE juknis_relation.juknis_id = {$model->id} AND juknis_relation.deleted_at IS NULL)")
                ->andWhere(['is', 'juknis_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is not', 'juknis_item.parent_id', new \yii\db\Expression('null')])
                ->andWhere(['juknis_item.status' => 1])
                ->orderBy(['juknis_item.id' => SORT_DESC]);

            $itemJuknisSearch    = new JuknisRelationSearch();
            $itemJuknisProvider   = $itemJuknisSearch->search(Yii::$app->request->queryParams);
            $itemJuknisProvider->query
                ->andWhere(['juknis_relation.juknis_id' => $model->id])
                ->andWhere(['is', 'juknis_relation.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'juknis_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['juknis_relation.status' => 1])
                ->andWhere(['juknis_item.status' => 1])
                ->orderBy(['id' => SORT_DESC]);

            return $this->render('view', [
                'model' => $model,
                'itemJuknisSearch' => $itemJuknisSearch,
                'itemJuknisProvider' => $itemJuknisProvider,
                'allItemJuknisSearch' => $allItemJuknisSearch,
                'allItemJuknisProvider' => $allItemJuknisProvider,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionUpdate($code)
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :

            $code = Yii::$app->encryptor->decodeUrl($code);

            $model = $this->findModel($code);
            $schools = ArrayHelper::getColumn($model->schools ? json_decode($model->schools) : [], 'id');

            $msg   = "";
            if ($model->load(Yii::$app->request->post())) :
                if ($model->schools) :
                    $modelSchool = PublicSekolah::find()->where(['in', 'sekolah_id', $model->schools])->all();
                    $school = ArrayHelper::getColumn($modelSchool, function ($data) {
                        return [
                            'id' => $data->sekolah_id,
                            'text' => $data->nama,
                        ];
                    });
                    $model->schools = json_encode($school);
                else :
                    $model->schools = NULL;
                endif;

                if ($model->save()) :
                    $msg = "Data berhasil di ubah";
                    Yii::$app->session->setFlash('success', $msg);
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'update', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('danger', $msg);
            endif;
            $year = ArrayHelper::index(range(date('Y') - 2, date('Y') + 2), function ($data) {
                return $data;
            });
            return $this->render('update', [
                'model' => $model,
                'schools' => ArrayHelper::map(PublicSekolah::find()
                    ->where(['in', 'sekolah_id', $schools])
                    ->all(), 'sekolah_id', 'nama'),
                'year' => $year,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionDelete()
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $code       = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
            $model = $this->findModel($code);

            if ($model) :
                $model->deleted_at = time();
                $model->deleted_by = Yii::$app->user->id;
                $model->save(false);
                return json_encode(['status' => 1]);
            endif;
            return json_encode(['status' => -1]);
        endif;
        return json_encode(['status' => -99]);
    }

    protected function findModel($id)
    {
        $model = Juknis::find()->where(['id' => $id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '')
    {
        $model = new Juknis();
        if ($code) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model      = Juknis::findOne($code);
        endif;
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    public function actionValidateItemJuknis($code = '')
    {
        $model = new JuknisItem();
        $model->scenario = "item-juknis";
        if ($code) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model      = JuknisItem::findOne($code);
            $model->scenario = "item-juknis";
        endif;
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    protected function generateCode($length)
    {
        $code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 10, $length);
        $model = Juknis::findOne(['code' => $code]);
        if (!$model) :
            return $code;
        else :
            return $this->generateCode($length);
        endif;
    }

    protected function generateCode2($length)
    {
        $code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 10, $length);
        $model = JuknisItem::findOne(['code' => $code]);
        if (!$model) :
            return $code;
        else :
            return $this->generateCode($length);
        endif;
    }

    public function actionGetSchools()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = PublicSekolah::find()
            ->where(['like', 'nama', $term])
            ->andWhere(['aktif' => 1])
            ->andWhere(['soft_delete' => 0])
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => $data->sekolah_id,
                'text' => $data->nama,
            ];
        });
        return json_encode(['results' => $data ?? []]);
    }

    public function actionGetParentJuknis()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = JuknisItem::find()
            ->where(['like', 'value', $term])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])
            ->andWhere(['status' => 1])
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->value,
            ];
        });
        return json_encode(['results' => $data ?? []]);
    }

    public function actionItemDelete()
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
            $model = JuknisRelation::findOne($code);
            if ($model) :
                $model->deleted_at = time();
                $model->deleted_by = Yii::$app->user->id;
                $model->save(false);
                return json_encode(['status' => 1]);
            endif;
            return json_encode(['status' => -1]);
        endif;
        return json_encode(['status' => -99]);
    }

    public function actionSwitchItem()
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $juknis_id = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('juknis_id'));
            $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));

            $model = new JuknisRelation();
            $model->juknis_id = $juknis_id;
            $model->juknis_item_id = $code;
            if ($model->save(false)) :
                return json_encode(['status' => 1]);
            endif;
            return json_encode(['status' => -1]);
        endif;
        return json_encode(['status' => -99]);
    }
}
