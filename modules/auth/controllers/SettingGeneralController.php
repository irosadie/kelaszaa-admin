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
    generals\Setting,
    searches\DanabosSetting as DanabosSettingSearch
};
use app\models\smart\{
    generals\PublicSekolah
};
use app\utils\{
    helper\Helper
};

class SettingGeneralController extends Controller
{
    public $title = "Pengaturan Applikasi";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'update', 'handle-file'],
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
            $data = Yii::$app->request->get();
            $model = Setting::find()->where(['like', 'name', 'app_%', false])->all();
            $data = ArrayHelper::map($model, 'name', 'value_');
            $year = ArrayHelper::index(range(date('Y') - 2, date('Y') + 2), function ($data) {
                return $data;
            });
            return $this->render('index', $data ? array_merge($data, ['year' => $year]) : ['year' => $year]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionUpdate()
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $data = Yii::$app->request->post();
            $tmp = 0;
            $msg = "";
            Yii::$app->response->format = Response::FORMAT_JSON;
            foreach ($data as $key => $value) :
                $model = Setting::find()->where(['name' => $key])->one();
                if ($model) :
                    $model->value_ = $value;
                    $model->save(false);
                    $tmp++;
                else :
                    continue;
                endif;
            endforeach;
            if ($tmp == count($data) - 1) :
                $msg = Yii::t('app', "Data berhasil di ubah");
                return ['status' => 1, 'from' => 'update', 'type' => null, 'msg' => $msg];
            endif;
            $msg = Yii::t('app', "Bebrapa data mungkin tidak berhasil diubah!");
            return ['status' => 0, 'from' => 'update', 'type' => null, 'msg' => $msg];
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }
}