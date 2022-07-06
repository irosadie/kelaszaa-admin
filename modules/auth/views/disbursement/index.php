<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Pencairan Dana');

?>
<?php Pjax::begin(['id' => 'p0']); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action tw-flex">
                        <?= isset(Yii::$app->request->get('Disbursement')['date_begin']) ? Html::button('<i class="fas fa-undo"></i> reset', ['data-href' => 'index', 'id' => 'reset', 'class' => 'mr-4 btn btn-warning tw- tw-whitespace-nowrap']) : '' ?>
                        <?= Html::button('<i class="fas fa-filter"></i> filter', ['data-href' => Yii::$app->homeurl . 'auth/disbursement/filter', 'id' => 'filter', 'class' => 'mr-4 btn btn-primary tw- tw-whitespace-nowrap']) ?>
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
                                    'attribute' => 'created_at',
                                    'label' => Yii::t('app', 'Tanggal'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return  date('d/m/Y', $model->created_at);
                                    }
                                ],
                                [
                                    'attribute' => 'period_id',
                                    'label' => Yii::t('app', 'Periode'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return $model->disbursementPlan->name;
                                    }
                                ],
                                [
                                    'attribute' => 'schools',
                                    'label' => 'School',
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:210px;'],
                                    'value' => function ($model) {
                                        return "<p class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-whitespace-nowrap tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>{$model->rkatItem->rkat->school->nama}</p>";
                                    }
                                ],
                                [
                                    'attribute' => 'desc',
                                    'label' => 'Deskripsi',
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:210px;'],
                                    'value' => function ($model) {
                                        return $model->desc;
                                    }
                                ],
                                [
                                    'attribute' => 'amount',
                                    'label' => 'Jumlah',
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:210px;'],
                                    'value' => function ($model) {
                                        return "<p class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit tw-whitespace-nowrap'>IDR " . number_format($model->validations && $model->validation_level == 'treasurer' ? $model->amount_approved : $model->amount_request, 0, '.', '.') . "</p>";
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => Yii::t('app', 'Status'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        return $model->validations ? "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'disetujui') . "</span>" : "<span class='tw-bg-yellow-400 tw-text-xs tw-px-3 tw-py-1 tw-rounded-full tw-text-white'>" . Yii::t('app', 'pengajuan') . "</span>";
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
function init(){
    $('#filter').click(function(e){
        e.preventDefault();
        var str = window.location.search.replace("?", "");
        $('#modalTitle').html('Filter')
        let url = $(this).data('href');
        $.get(url+(str?("?"+str):""), function(data) {
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    })
    $('#reset').click(function(e){
        $.pjax({url:'index', container:'#p0', timeout: false});
    })
}
init();
$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
    init();
});
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>