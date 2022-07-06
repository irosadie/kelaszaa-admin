<?php

namespace app\modules\auth\controllers;

use Yii;
use app\components\encrypt\Encryptor;
use app\components\gdrive\GDrive;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use app\models\{
    identities\Users
};
use yii\web\{
    Controller,
    NotFoundHttpException,
    UploadedFile,
    Response
};

use yii\filters\{
    VerbFilter,
    AccessControl
};

use yii\widgets\ActiveForm;

class ProfilesController extends \yii\web\Controller
{
    public $title = 'profile';

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'me', 'change-password', 'update', 'upload-photo', 'validate', 'handle-file'],
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

    public function beforeAction($action)
    {
        if ($action->id == 'handle-file') :
            Yii::$app->request->enableCsrfValidation = false;
        endif;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->redirect('me');
    }

    public function actionMe()
    {
        return $this->render('me', [
            'model' => $this->findModel(Yii::$app->user->id),
        ]);
    }

    public function actionChangePassword()
    {
        $model = Users::findOne(Yii::$app->user->id);
        $modelLock = json_encode($model->toArray());

        $model->scenario = 'change-password';
        $msg = "";

        if ($model->load(Yii::$app->request->post())) :
            $model->updated_at = time();
            $model->updated_by = Yii::$app->user->id;
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->new_password);
            if ($model->save()) :
                //save to logs
                Yii::$app->logs->save($model::tableName(), "UPDATE_PROFILE_PWD", $modelLock, json_encode($model->toArray()));

                $msg = "Password berhasil di ubah";
                Yii::$app->session->setFlash('success', $msg);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => 1, 'id' => null, 'from' => 'update', 'type' => null, 'msg' => $msg];
            endif;
            $err = $model->getErrors();
            $msg = $err[key($err)][0];
            Yii::$app->session->setFlash('danger', $msg);
        endif;

        return $this->render('change_password', [
            'model' => $model
        ]);
    }


    public function actionUploadPhoto()
    {
        $model = Users::findOne(Yii::$app->user->id);
        $modelLock = json_encode($model->toArray());

        $msg = "";
        if ($model->load(Yii::$app->request->post())) :
            $model->updated_at = time();
            $model->updated_by = Yii::$app->user->id;
            if ($model->save()) :
                //save to logs
                Yii::$app->logs->save($model::tableName(), "UPDATE_PROFILE_PHOTO", $modelLock, json_encode($model->toArray()));

                $msg = "Data berhasil di ubah";
                Yii::$app->session->setFlash('success', $msg);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => 1, 'id' => null, 'from' => 'update', 'type' => null, 'msg' => $msg];
            endif;
            $err = $model->getErrors();
            $msg = $err[key($err)][0];
            Yii::$app->session->setFlash('danger', $msg);
        endif;

        return $this->render('upload_photo', [
            'model' => $model,
        ]);
    }

    public function actionUpdate()
    {
        if (Yii::$app->user->can('/profiles/view') || 1) :
            $encryptor  = new Encryptor();

            $model = $this->findModel(Yii::$app->user->id);
            $modelLock = json_encode($model->toArray());

            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                $model->updated_at = time();
                $model->updated_by = Yii::$app->user->id;
                if ($model->save()) :
                    //save to logs
                    Yii::$app->logs->save($model::tableName(), "UPDATE_PROFILE", $modelLock, json_encode($model->toArray()));

                    $msg = "Data berhasil di ubah";
                    Yii::$app->session->setFlash('success', $msg);
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['status' => 1, 'id' => $encryptor->encodeUrl($model->id), 'from' => 'update', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('danger', $msg);
            endif;
            return $this->render('update', [
                'model' => $model,
                'encryptor' => $encryptor,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionValidate($scenario = '')
    {
        $model = Users::findOne(Yii::$app->user->id);
        if ($scenario) :
            $model->scenario = $scenario;
        endif;
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    #======================================================================

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
        } catch (Exception $e) {
            Yii::$app->response->statusCode = 500;
        }
    }
}
