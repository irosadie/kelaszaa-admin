<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Manajemen Mentor');

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <p>
                <?= Yii::$app->users->can([]) ? Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'mentor'), ['create'], ['class' => 'btn btn-info m-1']) : "" ?>
            </p>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action">
                        <?= $this->render('_search', ['model' => $searchModel, 'name' => 'query']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            // 'filterModel' => $searchModel,
                            'tableOptions' => ['class' => 'table table-striped'],
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'contentOptions' => ['style' => 'width:10px;'],
                                    'header' => 'No.'
                                ],
                                [
                                    'attribute' => 'avatar',
                                    'label' => Yii::t('app', 'Avatar'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:96px;'],
                                    'value' => function ($model) {
                                        $avatarUri = Yii::$app->setting->app('def_avt');
                                        if ($model->avatar) :
                                            $avatarUri = $model->avatar;
                                        endif;
                                        return "<img class='tw-w-full tw-h-auto lazy' src='{$avatarUri}' />";
                                    }
                                ],
                                [
                                    'attribute' => 'username',
                                    'label' => Yii::t('app', 'Nama Pengguna'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:240px;'],
                                    'value' => function ($model) {
                                        return  $model->username ?? "";
                                    }
                                ],
                                [
                                    'attribute' => 'full_name',
                                    'label' => Yii::t('app', 'Nama Lengkap'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:240px;'],
                                    'value' => function ($model) {
                                        return $model->full_name ? "<span class='tw-whitespace-nowrap'>{$model->full_name}</span>" : "";
                                    }
                                ],
                                [
                                    'attribute' => 'phone',
                                    'label' => Yii::t('app', 'Kontak'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:240px;'],
                                    'value' => function ($model) {
                                        return  $model->phone ?? "";
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => Yii::t('app', 'Status'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        switch ($model->status):
                                            case 10:
                                                return "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'aktif') . "</span>";
                                                break;
                                            case 9:
                                                return "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'belum konfirmasi') . "</span>";
                                                break;
                                            case 0:
                                                return "<span class='tw-bg-red-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'tidak aktif') . "</span>";
                                                break;
                                            default:
                                                return "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'lainnya') . "</span>";
                                                break;
                                        endswitch;
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width:150px;'],
                                    'header' => 'Action',
                                    'visibleButtons' => [
                                        'update' => false,
                                        'delete' => false,
                                        'view' => true,
                                    ],
                                    'template' => '{view}',
                                    'buttons' => array(
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-file"></i> ' . Yii::t('app', 'detail'), Url::to(['view', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-primary m-1 tw-whitespace-nowrap']);
                                        }
                                    )
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>