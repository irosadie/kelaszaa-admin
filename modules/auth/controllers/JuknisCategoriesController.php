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
    generals\JuknisItem,
    generals\Settings,
    searches\JuknisItem as JuknisItemSearch
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

class JuknisCategoriesController extends Controller
{
    public $title = "Kategori Juknis";

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
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $searchModel    = new JuknisItemSearch();

            $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])
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
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            if (!Yii::$app->request->isAjax) :
                return $this->redirect('index');
            endif;

            $model      = new JuknisItem();
            $msg        = "";
            if ($data = Yii::$app->request->post('JuknisItem')) :
                $status = 0;
                foreach ($data['value'] as $key => $value) :
                    if ($value && $value != "") :
                        $check = JuknisItem::find()
                            ->where(['value' => $value])
                            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
                            ->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])
                            ->one();
                        if ($check) :
                            continue;
                        endif;
                        $model = new $model;
                        $model->code = $this->generateCode(6);
                        $model->value = $value;
                        $model->status = 1;
                        if ($model->save(false)) :
                            $status += 1;
                        endif;
                    endif;
                endforeach;
                if ($status == count($data['value'])) :
                    $msg = Yii::t('app', "Data berhasil di tambah");
                    Yii::$app->session->setFlash('success', $msg);
                else :
                    $msg = Yii::t('app', "1 atau beberapa data gagal di tambah, mungkin karena kosong atau ganda");
                    Yii::$app->session->setFlash('danger', $msg);
                endif;
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => 1, 'id' => NULL, 'from' => 'create', 'type' => null, 'msg' => $msg];
            endif;
            return $this->renderAjax('create', [
                'model' => $model,
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
                $check = JuknisItem::find()
                    ->where(['value' => $model->value])
                    ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
                    ->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])
                    ->andWhere(['!=', 'id', $model->id])
                    ->one();
                if (!$check) :
                    if ($model->save()) :
                        $msg = Yii::t('app', "Data berhasil di ubah");
                        Yii::$app->session->setFlash('success', $msg);
                    endif;
                    $err = $model->getErrors();
                    $msg = $err[key($err)][0];
                else :
                    $msg = Yii::t('app', "Kategori yang sama sudah ada");
                    Yii::$app->session->setFlash('danger', $msg);
                endif;
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'update', 'type' => null, 'msg' => $msg];

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

    protected function findModel($id)
    {
        $model = JuknisItem::find()->where(['id' => $id])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '', $validate = 1)
    {
        if (!$validate) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [];
        endif;
        $model = new JuknisItem();
        if ($code) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model      = JuknisItem::findOne($code);
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
        $model = JuknisItem::findOne(['code' => $code]);
        if (!$model) :
            return $code;
        else :
            return $this->generateCode($length);
        endif;
    }
}
