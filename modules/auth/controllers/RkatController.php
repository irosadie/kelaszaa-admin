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
    generals\Rkat,
    generals\Juknis,
    searches\Rkat as RkatSearch,
    searches\JuknisRelation as JuknisRelationSearch,
    searches\RkatItem as RkatItemSearch,
    generals\RkatItem
};
use app\models\smart\{
    generals\PublicSekolah
};
use app\utils\{
    helper\Helper
};


use yii\widgets\ActiveForm;

class RkatController extends Controller
{
    public $title = "RKAT";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'validate', 'handle-file', 'get-schools', 'get-juknis', 'item-delete', 'switch-item', 'update-amount', 'update-amounts', 'item-detail', 'approve'],
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
        if (Yii::$app->users->can([])) :
            $searchModel = new RkatSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if (Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"]) && !Yii::$app->users->can(["operator", "treasurer"])) :
                $schools = ArrayHelper::getColumn(Yii::$app->session->get('user_grant')['schools'], 'id');
                $dataProvider->query->andWhere(['in', 'school_id', $schools]);
            endif;
            $dataProvider->query
                ->andWhere(['is', 'deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['status' => 1])
                ->andWhere(['year' => Yii::$app->setting->app('cfg_year')])
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
        if (Yii::$app->users->can(["operator"])) :
            $model = new Rkat();
            $msg = "";
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

    public function actionView($code)
    {
        $code       = Yii::$app->encryptor->decodeUrl($code);
        $model = $this->findModel($code); //model RKAT
        if (Yii::$app->users->can(["operator", "treasurer"]) || (Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], [$model->school_id]))) :
            $itemJuknisSearch = new JuknisRelationSearch();
            $itemJuknisProvider = $itemJuknisSearch->search(Yii::$app->request->queryParams);
            $itemJuknisProvider->query
                ->joinWith('rkatItems')
                ->andWhere("juknis_relation.id NOT IN (SELECT rkat_item.juknis_relation_id FROM rkat_item WHERE rkat_item.rkat_id = {$model->id} AND rkat_item.deleted_at IS NULL)")
                ->andWhere(['juknis_relation.juknis_id' => $model->juknis_id])
                ->andWhere(['is', 'juknis_relation.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'juknis_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['juknis_relation.status' => 1])
                ->andWhere(['juknis_item.status' => 1])
                ->orderBy(['id' => SORT_DESC]);

            $itemRkatSearch = new RkatItemSearch();
            $itemRkatProvider = $itemRkatSearch->search(Yii::$app->request->queryParams);
            $itemRkatProvider->query
                ->andWhere(['rkat_id' => $model->id])
                ->andWhere(['is', 'rkat_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'juknis_item.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['is', 'juknis_relation.deleted_at', new \yii\db\Expression('null')])
                ->andWhere(['rkat_item.status' => 1])
                ->andWhere(['juknis_item.status' => 1])
                ->andWhere(['juknis_relation.status' => 1])
                ->orderBy(['id' => SORT_DESC]);

            return $this->render('view', [
                'model' => $model,
                'itemJuknisSearch' => $itemJuknisSearch,
                'itemJuknisProvider' => $itemJuknisProvider,
                'itemRkatSearch' => $itemRkatSearch,
                'itemRkatProvider' => $itemRkatProvider,
            ]);

            return $this->render('view', [
                'model' => $model
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionItemDetail($code)
    {
        $dict = ["person_responsible" => "PJ Unit", "school_treasurer" => "Bendahara Sekolah", "headmaster" => "Kepala Sekolah", "treasurer" => "Bendahara Yayasan"];
        $code = Yii::$app->encryptor->decodeUrl($code);
        $model = RkatItem::findOne($code);
        if (Yii::$app->users->can(["operator", "treasurer"]) || Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], [$model->rkat->school_id])) :
            $next = false;
            if ($model->amount_estimate <= 0) :
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Pengajuan dana belum selesai, item ini tidak bisa disetujui'));
            else :
                $setting = Yii::$app->setting->app('flow_rkat');
                $flow = json_decode($setting, TRUE);
                $index = array_search($model->validation_level, array_values($flow));
                if ($index < count($flow) - 1) :
                    $next = $flow[$index + 1];
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Menunggu persetujuan dari ' . $dict[$next]));
                endif;
            endif;

            $canValidate = false;
            if (in_array($next, ["school_treasurer", "headmaster"])) :
                if (Yii::$app->users->can([$next], [$model->rkat->school_id])) :
                    $canValidate = true;
                endif;
            else :
                if (Yii::$app->users->can([$next])) :
                    $canValidate = true;
                endif;
            endif;

            return $this->render('item-detail', [
                'model' => $model,
                'canValidate' => $canValidate,
                'next' => $next,
                'dict' => $dict,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionApprove()
    {
        $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
        $amount = Yii::$app->request->post('amount');
        $as = Yii::$app->request->post('as');
        $model = RkatItem::findOne($code);
        $grant = false;

        if (in_array($as, ["school_treasurer", "headmaster"])) :
            if (Yii::$app->users->can([$as], [$model->rkat->school_id])) :
                $grant = true;
            endif;
        else :
            if (Yii::$app->users->can([$as])) :
                $grant = true;
            endif;
        endif;

        if ($grant) :
            $tmp = json_decode($model->validations, TRUE);
            $validation = [
                'user_id' => Yii::$app->user->id,
                'user_name' => Yii::$app->user->identity->username,
                'full_name' => Yii::$app->user->identity->pegawaiU->nama ?? "",
                'amount' => $amount,
                'role' => Yii::$app->session->get('user_grant')['levels'][0] ?? "",
                'as' => $as ?? "",
                'time' => time()
            ];
            if ($tmp) :
                array_push($tmp, $validation);
            else :
                $tmp =  [$validation];
            endif;
            $model->validations = json_encode($tmp);
            $model->amount_estimate = $amount;
            $model->validation_level = $as ?? "";
            $model->updated_by = Yii::$app->user->id;
            if ($model->save(false)) :
                return json_encode(['status' => 1]);
            endif;
            return json_encode(['status' => -1]);
        endif;
        return json_encode(['status' => -99]);
    }

    public function actionUpdate($code)
    {
        if (Yii::$app->users->can(["operator"])) :
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
            $year = ArrayHelper::index(range(date('Y') - 2, date('Y') + 2), function ($data) {
                return $data;
            });
            return $this->render('update', [
                'model' => $model,
                'schools' => ArrayHelper::map(PublicSekolah::find()
                    ->where(['sekolah_id' => $model->school_id])
                    ->all(), 'sekolah_id', 'nama'),
                'juknis' => ArrayHelper::map(Juknis::find()
                    ->where(['id' => $model->juknis_id])
                    ->all(), 'id', 'name'),
                'year' => $year,
            ]);
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }

    public function actionDelete()
    {
        if (Yii::$app->users->can(["operator"])) :
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
        return json_encode(['status' => -99]);;
    }

    public function actionItemDelete()
    {
        $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
        $model = RkatItem::findOne($code);
        if (Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["person_responsible", "school_treasurer"], [$model->rkat->school_id])) :
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

    public function actionSwitchItem()
    {
        $rkat_id = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('rkat_id'));
        $code = Yii::$app->encryptor->decodeUrl(Yii::$app->request->post('code'));
        $check = Rkat::findOne($rkat_id);
        if (Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["person_responsible", "school_treasurer"], [$check->school_id])) :
            $model = new RkatItem();
            $model->rkat_id = $rkat_id;
            $model->juknis_relation_id = $code;
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
        $model = new Rkat();
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

    public function actionUpdateAmounts()
    {
        if (Yii::$app->users->can(["operator", "person_responsible"])) :
            $data = Yii::$app->request->post('amount_estimate');
            $tmp = 0;
            $tmp2 = 0;
            $status = true;
            foreach ($data as $key => $value) :
                $value = str_replace(".", "", $value);
                if ($value) :
                    $tmp2++;
                    $model = RkatItem::findOne($key);
                    if (!Yii::$app->users->can(["operator"]) && !Yii::$app->users->can(["person_responsible"], [$model->rkat->school_id])) :
                        $status = false;
                        break;
                    endif;
                    if ($model->amount_estimate != $value) :
                        $model->validations = json_encode([[
                            'user_id' => Yii::$app->user->id,
                            'user_name' => Yii::$app->user->identity->username,
                            'full_name' => Yii::$app->user->identity->pegawaiU->nama ?? "",
                            'amount' => $value,
                            'role' => Yii::$app->session->get('user_grant')['levels'][0] ?? "", //ambil role yang pertama - bisa jadi dia adalah operator
                            'as' => "person_responsible",
                            'time' => time()
                        ]]);
                        $model->validation_level = 'person_responsible';
                    endif;
                    $model->amount_estimate = $value;
                    if ($model->save(false)) :
                        $tmp++;
                    endif;
                endif;
            endforeach;
            if (!$status) :
                return json_encode(['status' => -99]);
            endif;
            if ($tmp == $tmp2) :
                return json_encode(['status' => 1, 'msg' => Yii::t('app', 'Data berhasil diubah')]);
            endif;
            return json_encode(['status' => -1, 'msg' => Yii::t('app', 'Data berhasil diubah, tapi beberapa mungkin gagal')]);
        endif;
        return json_encode(['status' => -99]);
    }
}