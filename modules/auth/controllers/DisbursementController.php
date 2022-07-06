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
    generals\Rkat,
    generals\RkatItem,
    generals\Juknis,
    generals\Disbursement,
    generals\DisbursementPlan,
    searches\RkatItem as RkatItemSearch,
    searches\Disbursement as DisbursementSearch,

    searches\Rkat as RkatSearch
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

class DisbursementController extends Controller
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
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'validate', 'handle-file', 'get-schools', 'get-periods', 'item-delete', 'approve', 'filter'],
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
        if (Yii::$app->users->can(["treasurer"])) :
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

    public function actionView($code)
    {
        if (Yii::$app->users->can(["treasurer"])) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code);
            $percentage = $model->rkatItem->getRemainingFunds(true) == 0 ? 0 : round($model->amount_request / $model->rkatItem->getRemainingFunds(true) * 100, 2);
            $max_disbursement = 0;
            if ($model->amount_request <= $model->rkatItem->getRemainingFunds(true)) :
                $max_disbursement = $model->amount_request;
            else :
                $max_disbursement = $model->rkatItem->getRemainingFunds(true);
            endif;
            return $this->render('view', [
                'percentage' => $percentage,
                'model' => $model,
                'max_disbursement' => $max_disbursement
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    protected function findModel($id)
    {
        $model = Disbursement::find()->where(['id' => $id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionApprove()
    {
        if (Yii::$app->users->can(["treasurer"])) :
            $code       = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
            $amount       = Yii::$app->request->post('amount');
            $model = $this->findModel($code);

            if ($model) :
                $validation = ['user_id' => Yii::$app->user->id, 'username' => Yii::$app->user->identity->username, 'full_name' => Yii::$app->user->identity->pegawaiU->nama, 'role' => 'treasurer', 'amount' => $amount, 'time' => time()];
                $model->amount_approved = str_replace('.', '', $amount);
                $model->validations = json_encode($validation);
                $model->validation_level = 'treasurer';
                $model->updated_by = Yii::$app->user->id;
                $model->save(false);
                return json_encode(['status' => 1]);
            endif;
            return json_encode(['status' => -1]);
        endif;
        return json_encode(['status' => -99]);
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

    public function actionGetPeriods()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = DisbursementPlan::find()
            ->joinWith('disbursementMaster')
            ->where(['like', 'disbursement_plan.name', $term])
            ->where(['disbursement_master.status' => 1])
            ->andWhere(['is', 'disbursement_plan.deleted_at', new \yii\db\Expression('null')])
            ->andWhere(['is', 'disbursement_master.deleted_at', new \yii\db\Expression('null')])
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
}