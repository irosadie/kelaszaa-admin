<?php

namespace app\modules\auth\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\{
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
    generals\Rkat,
    generals\RkatItem,
    generals\Juknis,
    generals\Disbursement,
    searches\RkatItem as RkatItemSearch,
    searches\Rkat as RkatSearch
};
use app\models\smart\{
    generals\PublicSekolah
};
use app\utils\{
    gdrive\GDrive,
    helper\Helper
};


use yii\widgets\ActiveForm;

class RkatDisbursementController extends Controller
{
    public $title = "Pencairan Dana";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'detail', 'create', 'update', 'delete', 'validate', 'handle-file', 'get-schools', 'get-juknis', 'get-store-name', 'item-delete'],
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
        if (Yii::$app->users->can(["operator", "headmaster", "person_responsible", "school_treasurer"])) :
            $searchModel    = new RkatSearch();
            $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
            if (Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"]) && !Yii::$app->users->can(["operator", "treasurer"])) :
                $schools = ArrayHelper::getColumn(Yii::$app->session->get('user_grant')['schools'], 'id');
                $dataProvider->query->andWhere(['in', 'school_id', $schools]);
            endif;
            $dataProvider->query
                ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['year' => Yii::$app->setting->app('cfg_year')])
                ->andWhere(['status' => 1])
                ->orderBy(['id' => SORT_DESC]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionCreate($code)
    {

        $code = Yii::$app->encryptor->decodeUrl($code);
        $rkatItem = RkatItem::findOne($code);
        if (!Yii::$app->request->isAjax) :
            return $this->redirect(['view', 'code' => Yii::$app->encryptor->encodeUrl($rkatItem->id)]);
        endif;
        if (Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["headmaster", "school_treasurer", "person_responsible"], [$rkatItem->rkat->school_id])) :
            $model = new Disbursement();
            $msg = "";
            $school_id = $rkatItem->rkat->school_id;
            $setting = Yii::$app->setting->getDisbursementPlan($school_id);
            $max_disbursement = $rkatItem->remainingFunds;
            if ($model->load(Yii::$app->request->post())) :
                $model->rkat_item_id = $code;
                $model->percentage = NULL;
                $model->amount_request = str_replace(".", "", $model->amount_request);
                $model->disbursement_plan_id = $setting->id;
                if (($model->amount_request <= $max_disbursement) && $model->save()) :
                    $msg = Yii::t('app', "Data berhasil di tambah");
                    Yii::$app->session->setFlash('success', $msg);
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'create', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('danger', $msg);

            endif;
            return $this->renderAjax('create', [
                'model' => $model,
                'max_disbursement' => $max_disbursement,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionView($code)
    {

        $code = Yii::$app->encryptor->decodeUrl($code);
        $model = $this->findModel($code);
        if (Yii::$app->users->can(["operator"]) || (Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], [$model->school_id]))) :
            $setting = Yii::$app->setting->getDisbursementPlan($model->school_id);
            $itemJuknisSearch    = new RkatItemSearch();
            $itemJuknisProvider   = $itemJuknisSearch->search(Yii::$app->request->queryParams);
            $itemJuknisProvider->query
                ->andWhere(['rkat_item.rkat_id' => $model->id])
                ->andWhere(['is', 'rkat_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['>', 'rkat_item.amount_estimate', 0])
                ->andWhere(['validation_level' => 'treasurer'])
                ->andWhere(['rkat_item.status' => 1])
                ->orderBy(['id' => SORT_DESC]);

            return $this->render('view', [
                'model' => $model,
                'setting' => $setting,
                'searchModel' => $itemJuknisSearch,
                'dataProvider' => $itemJuknisProvider,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionDetail($code)
    {
        $code = Yii::$app->encryptor->decodeUrl($code);
        $model = Disbursement::findOne($code);
        if (Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], [$model->rkatItem->rkat->school_id])) :
            $percentage = round($model->amount_request / $model->rkatItem->getRemainingFunds(true) * 100, 2);
            $max_disbursement = 0;
            if ($model->amount_request <= $model->rkatItem->getRemainingFunds(true)) :
                $max_disbursement = $model->amount_request;
            else :
                $max_disbursement = $model->rkatItem->getRemainingFunds(true);
            endif;
            return $this->render('detail', [
                'percentage' => $percentage,
                'model' => $model,
                'max_disbursement' => $max_disbursement
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionUpdate($code)
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            if (!Yii::$app->request->isAjax) :
                return $this->redirect('index');
            endif;

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
            return $this->renderAjax('update', [
                'model' => $model,
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

    public function actionItemDelete()
    {
        $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
        $model = Disbursement::findOne($code);
        if (Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], $model->rkatItem->rkat->school_id)) :
            $model->deleted_at = time();
            $model->deleted_by = Yii::$app->user->id;
            if ($model->save(false)) :
                return json_encode(['status' => 1]);
            endif;
            return json_encode(['status' => -1]);
        endif;
        return json_encode(['status' => -99]);
    }

    protected function findModel($id)
    {
        $model = Rkat::find()->where(['id' => $id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '', $validate = 1)
    {
        $model = new Disbursement();
        if ($code) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model      = Rkat::findOne($code);
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
        $model = Rkat::findOne(['code' => $code]);
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

    public function actionGetJuknis()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = juknis::find()
            ->where(['like', 'name', $term])
            ->andWhere(['status' => 1])
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->name,
            ];
        });
        return json_encode(['results' => $data ?? []]);
    }

    public function actionGetStoreName()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = Disbursement::find()
            ->where(['like', 'store_name', $term])
            ->having("COUNT(store_name) >= 1")
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => trim($data->campus_str),
                'text' => trim($data->campus_str),
            ];
        });
        if (strlen($term) >= 3 && !in_array(strtolower($term), ArrayHelper::getColumn($data, function ($data) {
            return strtolower($data['id']);
        }))) :
            array_unshift($data, ['id' => trim($term), 'text' => trim($term . " 🧩")]);
        endif;
        return json_encode(['results' => $data ?? []]);
    }

    public function actionHandleFile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') :
            Yii::$app->response->statusCode = 200;
            return;
        endif;
        $tmp1 = array_key_first($_FILES);
        $tmp2 = array_key_first($_FILES[$tmp1]['name']);
        $fileIs = "$tmp1" . "[" . $tmp2 . "]";
        try {
            $file = UploadedFile::getInstanceByName($fileIs);
            $gdrive = new GDrive();
            $_file = $gdrive->uploadFile($file->name, $file->tempName, $file->type);
            Yii::$app->response->statusCode = 200;
            return Yii::$app->params['drive']['urlOpen'] . $_file;
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
        }
    }
}