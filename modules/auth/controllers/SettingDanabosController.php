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
use app\models\danabos\{
    generals\DanabosSettingDetail,
    generals\DanabosSetting,
    searches\DanabosSetting as DanabosSettingSearch
};
use app\models\smart\{
    generals\PublicSekolah
};
use app\utils\{
    helper\Helper
};


use yii\widgets\ActiveForm;

class SettingDanabosController extends Controller
{
    public $title = "Setting Dana Bos";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'validate', 'update', 'delete', 'get-schools', 'handle-file', 'update-disbursement-plan'],
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
            $searchModel    = new DanabosSettingSearch();

            $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['or', ['status' => 1], ['status' => 2]])
                ->orderBy(['id' => SORT_DESC]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'month' => Helper::getMonthList(),
            ]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionCreate()
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $model      = new DanabosSetting();
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
                    //save into disbursement_plan
                    for ($a = 0; $a < $model->disbursement_in_year; $a++) :
                        $DanabosSettingDetail = new DanabosSettingDetail();
                        $DanabosSettingDetail->disbursement_master_id = $model->id;
                        $DanabosSettingDetail->name = 'Q' . ($a + 1);
                        $DanabosSettingDetail->percentage_estimate = round(100 / $model->disbursement_in_year);
                        $DanabosSettingDetail->amount_estimate = 0;
                        $DanabosSettingDetail->save(false);
                    endfor;

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

    public function actionUpdateDanabosSettingDetail()
    {
        try {
            $data = Yii::$app->request->post();
            foreach ($data as $key => $value) :
                foreach ($value as $k => $v) :
                    $model = DanabosSettingDetail::findOne($k);
                    $model->{$key} = $v;
                    $model->save(false);
                endforeach;
            endforeach;
            return json_encode(['status' => 1]);
        } catch (\Exception $e) {
            return json_encode(['status' => -1]);
        }
    }

    public function actionUpdate($code)
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :

            $code = Yii::$app->encryptor->decodeUrl($code);

            $model = $this->findModel($code);
            $schools = ArrayHelper::getColumn($model->schools ? json_decode($model->schools) : [], 'id');
            $keep_disbursement = $model->disbursement_in_year;
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
                $model->disbursement_in_year = $keep_disbursement;
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
            return $this->render('update', [
                'model' => $model,
                'schools' => ArrayHelper::map(PublicSekolah::find()
                    ->where(['in', 'sekolah_id', $schools])
                    ->all(), 'sekolah_id', 'nama'),
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
        $model = DanabosSetting::find()->where(['id' => $id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '')
    {
        $model = new DanabosSetting();
        if ($code) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = DanabosSetting::findOne($code);
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
        $model = DanabosSetting::findOne(['code' => $code]);
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
}
