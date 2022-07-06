<?php

namespace app\modules\auth\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\{
    Controller,
    NotFoundHttpException,
    ForbiddenHttpException,
    Response,
};
use yii\filters\{
    VerbFilter,
    AccessControl
};
use app\models\mains\{
    generals\Classes,
    searches\Classes as ClassesSearch,
};


use yii\widgets\ActiveForm;

class ClassController extends Controller
{
    public $title = "Kelas";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'validate', 'handle-file'],
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
        if (Yii::$app->users->can([])) :
            $searchModel = new ClassesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if (Yii::$app->users->can(["mentor"]) && !Yii::$app->users->can(["operator", "treasurer"])) :
                $schools = ArrayHelper::getColumn(Yii::$app->session->get('user_grant')['schools'], 'id');
                $dataProvider->query->andWhere(['in', 'school_id', $schools]);
            endif;
            $dataProvider->query
                ->andWhere(['deleted_at' => NULL])
                ->andWhere(['status' => 1])
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
        if (Yii::$app->users->can([])) :
            $model = new Classes();
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
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

    public function actionView($code)
    {
        $code       = Yii::$app->encryptor->decodeUrl($code);
        $model = $this->findModel($code); //model Classes
        if (Yii::$app->users->can(["operator", "treasurer"]) || (Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], [$model->school_id]))) :
            $itemJuknisSearch = new JuknisRelationSearch();
            $itemJuknisProvider = $itemJuknisSearch->search(Yii::$app->request->queryParams);
            $itemJuknisProvider->query
                ->joinWith('ClassesItems')
                ->andWhere("juknis_relation.id NOT IN (SELECT Classes_item.juknis_relation_id FROM Classes_item WHERE Classes_item.Classes_id = {$model->id} AND Classes_item.deleted_at IS NULL)")
                ->andWhere(['juknis_relation.juknis_id' => $model->juknis_id])
                ->andWhere(['is', 'juknis_relation.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'juknis_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['juknis_relation.status' => 1])
                ->andWhere(['juknis_item.status' => 1])
                ->orderBy(['id' => SORT_DESC]);

            $itemClassesSearch = new ClassesItemSearch();
            $itemClassesProvider = $itemClassesSearch->search(Yii::$app->request->queryParams);
            $itemClassesProvider->query
                ->andWhere(['Classes_id' => $model->id])
                ->andWhere(['is', 'Classes_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'juknis_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'juknis_relation.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['Classes_item.status' => 1])
                ->andWhere(['juknis_item.status' => 1])
                ->andWhere(['juknis_relation.status' => 1])
                ->orderBy(['id' => SORT_DESC]);

            return $this->render('view', [
                'model' => $model,
                'itemJuknisSearch' => $itemJuknisSearch,
                'itemJuknisProvider' => $itemJuknisProvider,
                'itemClassesSearch' => $itemClassesSearch,
                'itemClassesProvider' => $itemClassesProvider,
            ]);

            return $this->render('view', [
                'model' => $model
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionUpdate($code)
    {
        if (Yii::$app->users->can(["operator"])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code);
            $msg   = "";
            if ($model->load(Yii::$app->request->post())) :
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
                    ->where(['sekolah_id' => $model->school_id])
                    ->all(), 'sekolah_id', 'nama'),
                'juknis' => ArrayHelper::map(Juknis::find()
                    ->where(['id' => $model->juknis_id])
                    ->all(), 'id', 'name'),
                'year' => $year,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionDelete()
    {
        if (Yii::$app->users->can(["operator"])) :
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
        return json_encode(['status' => -99]);;
    }

    protected function findModel($id)
    {
        $model = Classes::find()->where(['id' => $id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '', $validate = 1)
    {
        $model = new Classes();
        if ($code) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model      = Classes::findOne($code);
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
        $model = Classes::findOne(['code' => $code]);
        if (!$model) :
            return $code;
        else :
            return $this->generateCode($length);
        endif;
    }
}