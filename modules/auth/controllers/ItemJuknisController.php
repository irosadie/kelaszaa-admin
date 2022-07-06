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
    generals\JuknisItem,
    searches\JuknisItem as JuknisItemSearch,
};
use app\models\smart\{
    generals\PublicSekolah
};
use app\utils\{
    helper\Helper
};


use yii\widgets\ActiveForm;

class ItemJuknisController extends Controller
{
    public $title = "Item Juknis";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'delete', 'create', 'update', 'validate', 'get-parent-juknis'],
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
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        if (Helper::in_arrays(["operator"], Yii::$app->session->get("user_grant")['levels'] ?? [])) :
            $searchModel    = new JuknisItemSearch();
            $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query
                ->joinWith('juknisRelations')
                ->andWhere(['is', 'juknis_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is not', 'juknis_item.parent_id', new \yii\db\Expression('null')])
                ->andWhere(['juknis_item.status' => 1])
                ->orderBy(['juknis_item.id' => SORT_DESC]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        endif;

        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionCreate()
    {
        $model->scenario = "item-juknis";
        $msg        = "";
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
        return $this->renderAjax('create', ['model' => $model]);
    }

    public function actionUpdate($code)
    {
        $code = Yii::$app->encryptor->decodeUrl($code);
        $model = $this->findModel($code);
        $model->scenario = "item-juknis";
        $msg        = "";
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
        return $this->renderAjax('update', [
            'model' => $model,
            'parents' => ArrayHelper::map(JuknisItem::find()->where(['id' => $model->parent_id])->all(), 'id', 'value'),
        ]);
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
        $model = new JuknisItem();
        $model->scenario = "item-juknis";
        if ($code) :
            $code       = Yii::$app->encryptor->decodeUrl($code);
            $model      = JuknisItem::findOne($code);
            $model->scenario = "item-juknis";
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

    public function actionGetParentJuknis()
    {
        $term = Yii::$app->request->get('search') ?? "";
        $term = trim($term);
        $model = JuknisItem::find()
            ->where(['like', 'value', $term])
            ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
            ->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])
            ->andWhere(['status' => 1])
            ->limit(20)
            ->all();
        $data = ArrayHelper::getColumn($model, function ($data) {
            return [
                'id' => $data->id,
                'text' => $data->value,
            ];
        });
        return json_encode(['results' => $data ?? []]);
    }
}
