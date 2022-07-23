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
    generals\Classes,
    generals\Members,
    generals\ClassMembers,
    generals\Topics,
    generals\LearningMaterials,
    searches\Classes as ClassesSearch,
    searches\Topics as TopicsSearch,
    searches\ClassMembers as ClassMembersSearch,
    searches\MeetSchedules as MeetSchedulesSearch
};
use app\models\{
    identities\Users
};
use yii\widgets\ActiveForm;
use app\utils\{
    gdrive\GDrive,
};
use yii\helpers\ArrayHelper;


class ClassesController extends Controller
{
    public $title = "Class Managements";

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
            $searchModel = new ClassesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['deleted_at' => NULL])
                ->andWhere(['or', ['status' => 1], ['status' => 0]])
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
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->thumbnail = $model->thumbnail ? $model->thumbnail : null;
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
            $model = $this->findModel($code); //model Users
            return $this->render('view', [
                'model' => $model
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionUpdate($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code);
            $oldThumbnail = $model->thumbnail;
            $msg   = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->thumbnail = $model->thumbnail ? $model->thumbnail : $oldThumbnail;
                if ($model->save()) :
                    $msg = Yii::t('app', 'Data Berhasil di Ubah');
                    Yii::$app->session->setFlash('success', $msg);
                    return ['status' => 1, 'id' => Yii::$app->encryptor->encodeUrl($model->id), 'from' => 'update', 'type' => null, 'msg' => $msg];
                endif;
                $err = $model->getErrors();
                $msg = $err[key($err)][0];
                Yii::$app->session->setFlash('success', $msg);
                return ['status' => 0, 'msg' => $msg];
            endif;
            return $this->render('update', [
                'model' => $model,
                'mentors' => ArrayHelper::map(Users::find()
                    ->where(['id' => $model->mentor_id])
                    ->all(), 'id', 'full_name'),
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

    public function actionMember($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code); //model Users
            $searchModel = new ClassMembersSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['deleted_at' => NULL])
                ->andWhere(['status' => 1])
                ->orderBy(['id' => SORT_DESC]);
            return $this->render('member', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionDetailMember($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModelClassMember($code);
            return $this->render('detail-member', [
                'model' => $model
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionAddMember($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $parent = $this->findModel($code);
            $model = new ClassMembers();
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
                $check = ClassMembers::findOne(['member_id' => $model->member_id, 'class_id' => $parent->id, 'status' => 1, 'deleted_at' => NULL]);
                if ($check) :
                    $msg = Yii::t('app', 'Pengguna ini sudah ada didalam kelas!');
                    Yii::$app->session->setFlash('danger', $msg);
                    return ['status' => 0, 'msg' => $msg];
                endif;
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
            return $this->renderAjax('add-member', [
                'model' => $model,
                'parent' => $parent,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionMeet($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code);

            $searchModel = new MeetSchedulesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->andWhere(['deleted_at' => NULL])
                ->andWhere(['status' => 1])
                ->orderBy(['id' => SORT_DESC]);
            return $this->render('meet', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionAddMeet($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $parent = $this->findModel($code);
            $model = new ClassMembers();
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
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
            return $this->renderAjax('add-meet', [
                'model' => $model,
                'parent' => $parent,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionGetMembers()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = Members::find()
            ->where(['like', 'full_name', $term])
            ->andWhere(['status' => 10])
            ->andWhere(['deleted_at' => NULL])
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->full_name . " - " . $data->username,
            ];
        });
        return json_encode(['results' => $data ?? []]);
    }

    public function actionDeleteMember()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
            $model = $this->findModelClassMember($code);
            if ($model->delete()) :
                return ['status' => 1];
            endif;
            return ['status' => -1];
        endif;
        return ['status' => -99];
    }

    public function actionRoom($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = $this->findModel($code);

            $searchModel = new TopicsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->where(['class_id' => $model->id])
                ->andWhere(['deleted_at' => NULL])
                ->andWhere(['status' => 1])
                ->orderBy(['id' => SORT_DESC]);
            return $this->render('room', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionAddTopic($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $parent = $this->findModel($code);
            $model = new Topics();
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
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
            return $this->renderAjax('add-topic', [
                'model' => $model,
                'parent' => $parent,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionAddMaterial($code)
    {
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $parent = $this->findModel($code);
            $model = new LearningMaterials();
            $msg = "";
            if ($model->load(Yii::$app->request->post())) :
                Yii::$app->response->format = Response::FORMAT_JSON;
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
            return $this->renderAjax('add-material', [
                'model' => $model,
                'parent' => $parent,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionBan()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
            $model = $this->findModelClassMember($code);
            if ($model->ban()) :
                return ['status' => 1];
            endif;
            return ['status' => -1];
        endif;
        return ['status' => -99];
    }

    public function actionAlumni()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->users->can([])) :
            $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
            $model = $this->findModelClassMember($code);
            if ($model->alumni()) :
                return ['status' => 1];
            endif;
            return ['status' => -1];
        endif;
        return ['status' => -99];
    }

    protected function findModelClassMember($id)
    {
        $model = ClassMembers::find()->where(['id' => $id])
            ->andWhere(['deleted_at' => NULL])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    protected function findModel($id)
    {
        $model = Classes::find()->where(['id' => $id])
            ->andWhere(['deleted_at' => NULL])
            ->one();
        if ($model !== null) :
            return $model;
        endif;

        throw new NotFoundHttpException('Page Not Found');
    }

    public function actionValidate($code = '')
    {
        $model = new Classes();
        if ($code) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = Classes::findOne($code);
        endif;
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    public function actionValidateMember()
    {
        $model = new ClassMembers();
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    public function actionValidateTopic($code = '')
    {
        $model = new Topics();
        if ($code) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = Topics::findOne($code);
        endif;
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    public function actionValidateMaterial($code = '')
    {
        $model = new LearningMaterials();
        if ($code) :
            $code = Yii::$app->encryptor->decodeUrl($code);
            $model = LearningMaterials::findOne($code);
        endif;
        if ($model->load(Yii::$app->request->post())) :
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        endif;
        return false;
    }

    public function actionGetMentors()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = Users::find()
            ->where(['like', 'full_name', $term])
            ->andWhere(['status' => 10])
            ->andWhere(['role' => 'mentor'])
            ->andWhere(['deleted_at' => NULL])
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->full_name,
            ];
        });
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