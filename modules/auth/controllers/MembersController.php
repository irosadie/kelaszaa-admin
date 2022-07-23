<?php

namespace app\modules\auth\controllers;

use Yii;
use yii\web\{
    Controller,
    NotFoundHttpException,
    ForbiddenHttpException,
    Response,
    UploadedFile
};
use yii\filters\{
    VerbFilter
};
use app\models\mains\{
    searches\Members as MembersSearch,
    generals\Members,
};
use app\models\{
    identities\Users
};
use yii\widgets\ActiveForm;
use app\utils\{
    gdrive\GDrive,
};
use yii\helpers\ArrayHelper;

class MembersController extends Controller
{
    public $title = "Member";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
            $searchModel = new MembersSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['deleted_at' => NULL])
                ->andWhere(['or', ['status' => 10], ['status' => 9], ['status' => 0], ['status' => -1]])
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
            $model = new Members();
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->avatar = $model->avatar ?? null;
                $model->username = $model->email;
                if ($model->save()) :
                    $msg = Yii::t('app', "Data berhasil di tambah");
                    Yii::$app->session->setFlash('success', $msg);
                    return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'create', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('danger', $msg);
                return ['status' => 0, 'msg' => $msg];
            endif;
            return $this->render('create', [
                'model' => $model
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionView($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code); //model Members
            return $this->render('view', [
                'model' => $model
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionUploadPhoto($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = Users::findOne($code);
            $model->scenario = 'update';
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->save()) :
                    $msg = Yii::t('app', "Data berhasil di ubah");
                    Yii::$app->session->setFlash('success', $msg);
                    return ['status' => 1, 'id' => null, 'from' => 'update', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('danger', $msg);
                return ['status' => 0, 'msg' => $msg];
            endif;

            return $this->render('upload_photo', [
                'model' => $model,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionUpdate($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code);
            $oldAvatar = $model->avatar;
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->username = $model->email;
                $model->avatar = $model->avatar ? $model->avatar : $oldAvatar;
                if ($model->save()) :
                    $msg = Yii::t('app', 'Data Berhasil di Ubah');
                    Yii::$app->session->setFlash('success', $msg);
                    return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'update', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('warning', $msg);
                return ['status' => 0, 'msg' => $msg];
            endif;
            return $this->render('update', [
                'model' => $model
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
            $model = $this->findModel($code);
            if ($model->delete()) :
                return ['status' => 1];
            endif;
            return ['status' => -1];
        endif;
        return ['status' => -99];
    }

    public function actionResetPassword()
    {
        //kirim email
        return 1;
    }

    protected function findModel($id)
    {
        $model = Members::find()->where(['id' => $id])
            ->andWhere(['deleted_at' => NULL])
            ->one();
        if ($model !== null) :
            return $model;
        endif;
        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '')
    {
        $model = new Members();
        if ($code) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = Members::findOne($code);
        endif;
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    public function actionGetAgencies()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = Members::find()
            ->where(['like', 'agency', $term])
            ->having("COUNT(agency) >= 1")
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => trim($data->agency),
                'text' => trim($data->agency),
            ];
        });
        if (strlen($term) >= 3 && !in_array(strtolower($term), ArrayHelper::getColumn($data, function ($data) {
            return strtolower($data['id']);
        }))) :
            array_unshift($data, ['id' => trim($term), 'text' => trim($term . "*")]);
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