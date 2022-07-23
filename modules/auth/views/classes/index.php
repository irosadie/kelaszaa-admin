<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Tambah Kelas');

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <p>
                <?= Yii::$app->users->can([]) ? Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'kelas'), ['create'], ['class' => 'btn btn-info m-1']) : "" ?>
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
                                    'attribute' => 'code',
                                    'label' => Yii::t('app', 'Kode'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:96px;'],
                                    'value' => function ($model) {
                                        return $model->code ?? "";
                                    }
                                ],
                                [
                                    'attribute' => 'title',
                                    'label' => Yii::t('app', 'Nama'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:180px;'],
                                    'value' => function ($model) {
                                        return  $model->title ?? "";
                                    }
                                ],
                                [
                                    'attribute' => 'mentor_id',
                                    'label' => Yii::t('app', 'Mentor'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        return $model->mentor->full_name ? "<span class='tw-whitespace-nowrap'>{$model->mentor->full_name}</span>" : "";
                                    }
                                ],
                                [
                                    'attribute' => 'price',
                                    'label' => Yii::t('app', 'Harga'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        return $model->price ? "Rp.<span class='tw-whitespace-nowrap'>" . number_format($model->price, 0, '.', '.') . "</span>" : "-";
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => Yii::t('app', 'Status'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        switch ($model->status):
                                            case 1:
                                                return "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'aktif') . "</span>";
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
                                        'view' => false,
                                    ],
                                    'template' => '{more}',
                                    'buttons' => array(
                                        'more' => function ($url, $model, $key) {
                                            return '<div class="dropdown d-inline">
                                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-file"></i> ' . Yii::t('app', 'aksi') . '
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            ' . Html::a(Yii::t('app', 'detail*'), Url::to(['view', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'dropdown-item tw-whitespace-nowrap']) . '
                                                            ' . Html::a(Yii::t('app', 'member*'), Url::to(['member', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'dropdown-item tw-whitespace-nowrap']) . '
                                                            ' . Html::a(Yii::t('app', 'room'), Url::to(['room', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'dropdown-item tw-whitespace-nowrap']) . '
                                                            ' . Html::a(Yii::t('app', 'meet*'), Url::to(['meet', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'dropdown-item tw-whitespace-nowrap']) . '
                                                        </div>
                                                    </div>';
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

$('.lazy').Lazy()
$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>