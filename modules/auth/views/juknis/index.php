<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'List Artikel';

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <p>
                <?= Html::a('<i class="fa fa-plus"></i> Juknis', ['create'], ['class' => 'btn btn-info m-1']) ?>
            </p>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action">
                        <?= $this->render('_search', ['model' => $searchModel]) ?>
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
                                    'attribute' => 'name',
                                    'label' => Yii::t('app', 'Nama Juknis'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return  $model->name ? "<strong>{$model->name}</strong>" : "";
                                    }
                                ],
                                [
                                    'attribute' => 'name',
                                    'label' => Yii::t('app', 'Tahun Anggaran'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return $model->year ? "<span class='tw-bg-red-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>{$model->year}</span>" : "";
                                    }
                                ],
                                [
                                    'attribute' => 'schools',
                                    'label' => Yii::t('app', 'Sekolah'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:210px;'],
                                    'value' => function ($model) {
                                        $isReturn = "";
                                        if ($model->schools) :
                                            $school = json_decode($model->schools);
                                            foreach ($school as $key => $val) :
                                                $isReturn .= "<p class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-whitespace-nowrap tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>{$val->text}</p>";
                                            endforeach;
                                        else :
                                            $isReturn = "<span class='tw-bg-blue-400 tw-px-3 tw-whitespace-nowrap tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>Semua Sekolah</span>";
                                        endif;
                                        return "<div>$isReturn</div>";
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => Yii::t('app', 'Status'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        return $model->status == 1 ? "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'Publish') . "</span>" : "<span class='tw-bg-blue-400 tw-text-xs tw-px-3 tw-py-1 tw-rounded-full tw-text-white'>" . Yii::t('app', 'Draf') . "</span>";
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
                                            return Html::a('<i class="fas fa-file"></i> ' . Yii::t('app', 'detail'), Url::to(['view', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-primary m-1']);
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