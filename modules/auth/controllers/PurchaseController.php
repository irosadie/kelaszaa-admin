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
    generals\DisbursementPlan,
    generals\PurchaseReport,
    searches\Disbursement as DisbursementSearch
};
use app\models\smart\{
    generals\PublicSekolah
};
use app\utils\{
    gdrive\GDrive,
    helper\Helper
};


use yii\widgets\ActiveForm;

class PurchaseController extends Controller
{
    public $title = "Laporan Pembelian Barang";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'validate', 'handle-file', 'get-schools', 'get-store-names', 'get-units', 'filter', 'get-store-info'],
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
            $searchModel    = new DisbursementSearch();
            $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['is', 'disbursement.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['disbursement.status' => 1])
                ->orderBy(['disbursement.id' => SORT_DESC]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionCreate($code)
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $model = new PurchaseReport();
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                $code = Yii::$app->encryptor->decodeUrl($code);
                $model->disbursement_id = $code;
                $model->amount_total = str_replace(".", "", $model->amount_total);
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
            return $this->renderAjax('create', [
                'model' => $model,
                'year' => $year,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionView($code)
    {
        if (Yii::$app->user->can('/companies/view') || 1) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code);
            return $this->render('view', [
                'model' => $model
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

    public function actionFilter()
    {
        $model    = new DisbursementSearch();
        return $this->renderAjax(
            '_filter',
            array_merge([
                'model' => $model,
                'schools' => ArrayHelper::map(PublicSekolah::find()
                    ->where(['sekolah_id' => Yii::$app->request->queryParams['Disbursement']['school_id'] ?? "."])
                    ->all(), 'sekolah_id', 'nama'),
                'periods' => ArrayHelper::map(DisbursementPlan::find()
                    ->joinWith('disbursementMaster')
                    ->where(['disbursement_master.status' => 1])
                    ->andWhere(['is', 'disbursement_plan.deleted_at', new \yii\db\Expression('null')])
                    ->andWhere(['is', 'disbursement_master.deleted_at', new \yii\db\Expression('null')])
                    ->all(), 'id', 'name'),
            ], Yii::$app->request->queryParams['Disbursement'] ?? [])
        );
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
        $model = PurchaseReport::find()->where(['id' => $id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '', $validate = 1)
    {
        $model = new PurchaseReport();
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

    public function actionGetStoreNames()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = PurchaseReport::find()
            ->where(['like', 'store_name', $term])
            ->having("COUNT(store_name) >= 1")
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => trim($data->store_name),
                'text' => trim($data->store_name),
            ];
        });
        if (strlen($term) >= 3 && !in_array(strtolower($term), ArrayHelper::getColumn($data, function ($data) {
            return strtolower($data['id']);
        }))) :
            array_unshift($data, ['id' => trim($term), 'text' => trim($term . "*")]);
        endif;
        return json_encode(['results' => $data ?? []]);
    }

    public function actionGetUnits()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = PurchaseReport::find()
            ->where(['like', 'unit_str', $term])
            ->having("COUNT(unit_str) >= 1")
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => trim($data->unit_str),
                'text' => trim($data->unit_str),
            ];
        });
        if (strlen($term) >= 3 && !in_array(strtolower($term), ArrayHelper::getColumn($data, function ($data) {
            return strtolower($data['id']);
        }))) :
            array_unshift($data, ['id' => trim($term), 'text' => trim($term . "*")]);
        endif;
        return json_encode(['results' => $data ?? []]);
    }

    public function actionGetStoreInfo($store_name)
    {
        $model = PurchaseReport::find()
            ->where(['store_name' => $store_name])
            ->one();
        $data = [];
        if ($model) :
            $data['phone'] = $model->store_phone;
            $data['address'] = $model->store_address;
        endif;
        return json_encode(['results' => $data]);
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
