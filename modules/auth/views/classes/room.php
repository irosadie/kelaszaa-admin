<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Topik Materi pada Kelas');

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="index">
    <div class="row">
        <div class="col-12 col-lg-8 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><?= Yii::t('app', 'Detail Kelas') ?></h4>
                </div>
                <div class="card-body pb-4 tw-space-y-4">
                    <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                        <div class="tw-w-full">
                            <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Nama') ?>:</p>
                            <p class="tw-text-sm tw-font-bold"><?= $model->title ?? "-" ?></p>
                        </div>
                        <div class="w-full">
                            <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Kode') ?>:</p>
                            <p
                                class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>
                                <?= $model->code ?? "-" ?></p>
                        </div>
                        <div class="w-full">
                            <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Harga') ?>:</p>
                            <p
                                class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-whitespace-nowrap tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>
                                <?= "Rp. " . number_format($model->price, 0, '.', '.') ?? "-" ?></p>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Mentor') ?>:</p>
                            <?= $model->mentor->full_name ? "<span class='tw-whitespace-nowrap'>{$model->mentor->full_name}</span>" : "" ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <tr>
                                <td><strong>Created at</strong></td>
                                <td>:</td>
                                <td><?= date("d/m/Y h:m:s", $model->created_at) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Created by</strong></td>
                                <td>:</td>
                                <td><?= $model->createdBy->full_name ?? "-" ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>:</td>
                                <td><?= $model->status == 1 ? "<span class='badge badge-success'>publish</span>" : "<span class='badge badge-primary'>draft</span>" ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group float-right">
                        <?= Html::a('<i class="fas fa-undo-alt"></i> ', 'index', ['class' => 'btn btn-sm btn-info m-1', 'data-pjax' => 1]);  ?>
                        <?= Html::a('<i class="fas fa-file"></i> ', Url::to(['view', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'fr' => 'm']), ['class' => 'btn btn-sm btn-primary m-1', 'data-pjax' => 1]);  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action tw-flex">
                        <?= isset(Yii::$app->request->get('Disbursement')['date_begin']) ? Html::button('<i class="fas fa-undo"></i> reset', ['data-href' => 'index', 'id' => 'reset', 'class' => 'mr-4 btn btn-warning tw- tw-whitespace-nowrap']) : '' ?>
                        <?= Html::button('<i class="fas fa-filter"></i> filter', ['data-href' => Yii::$app->homeurl . 'auth/purchase/filter', 'id' => 'filter', 'class' => 'mr-4 btn btn-primary tw- tw-whitespace-nowrap']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'tableOptions' => ['class' => 'table table-striped'],
                            'afterRow' => function ($model, $key, $index, $grid) {
                                $date = Yii::t('app', 'Thumbnail');
                                $item = Yii::t('app', 'Kode Materi');
                                $total = Yii::t('app', 'Judul');
                                $amount = Yii::t('app', 'Jenis');
                                $receipt = Yii::t('app', 'Kapasitas');
                                $action = Yii::t('app', 'Aksi');
                                $reports = "<tr><td colspan='7' style='text-align:center;'>Tidak/ Belum ada Materi</td></tr>";
                                if ($model->learningMaterials) :
                                    $reports = "";
                                    foreach ($model->learningMaterials as $iskey => $isvalue) :
                                        $dropdown = '<div class="btn-group mb-2">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    ' . Yii::t('app', 'aksi') . '
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);">
                                                        <a href="view?code=' . Yii::$app->encryptor->encodeUrl($isvalue->id) . '" class="dropdown-item" href="#">detail</a>
                                                        <a class="dropdown-item delete" data-key="' . $key . '" data-id="' . Yii::$app->encryptor->encodeUrl($isvalue->id) . '" href="#">hapus</a>
                                                    </div>
                                                </div>';
                                        $reports .= '<tr><td>#</td><td><img class="tw-w-full tw-h-auto lazy" src="' . ($isvalue->thumbnail ?? Yii::$app->setting->app('def_avt')) . '" /></td><td>' . ($isvalue->code ?? "-") . '</td><td>' . ($isvalue->title ?? "-") . '</td><td> ' . ($isvalue->media_type ?? "-") . '</td><td>' . ($isvalue->media_weight ?? "-") . '</td><td>' . $dropdown . '</td></tr>';
                                    endforeach;
                                    $remaining = 0;
                                    $reports .= "<tr><td>&nbsp;</td><td colspan='3'><strong>Total</strong></td><td colspan='3'> <strong>IDR " . '-' . "</strong></td></tr>";
                                    $reports .= "<tr><td>&nbsp;</td><td colspan='3'><strong>Sisa Dana</strong></td><td colspan='3'> <strong style='" . ($remaining ? "color:red;" : "") . "'>IDR " . number_format(($remaining), 0, '.', '.') . "</strong></td></tr>";
                                endif;
                                return '<tr class="row-collapse collapse-' . $key . '" style="display: none;">
                                        <td colspan="12" class="p-0">
                                            <div class="card-header">
                                                <h4>Daftar Materi (Video dan File)</h4>
                                            </div>
                                            <div class="row" style="margin:0px; padding-bottom:12px; background-color:#fefefe;">
                                                <table class="table table-sm"><tr><td style="width:32px;">#</td><th style="width:128px;">' . $date . '</th><th style="width:320px;">' . $item . '</th><th style="width:96px;">' . $total . '</th><th>' . $amount . '</th><th>' . $receipt . '</th><th>' . $action . '</th></tr>' . $reports . '</table>
                                            </div>
                                        </td>
                                    </tr>';
                            },
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'contentOptions' => ['style' => 'width:10px;'],
                                    'header' => 'No.'
                                ],
                                [
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:32px;'],
                                    'value' => function ($model, $key) {
                                        return '<i data-key="' . $key . '" style="cursor: pointer; border-radius:999px" class="fas fa-arrow-down btn-collapse p-2 btn-warning"></i>';
                                    }
                                ],
                                [
                                    'label' => Yii::t('app', 'Kode'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:96px;'],
                                    'value' => function ($model) {
                                        $code = $model->code ?? "-";
                                        return "<span class='tw-text-bold'>{$code}</span>";
                                    }
                                ],
                                [
                                    'label' => Yii::t('app', 'Topik'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:240px;'],
                                    'value' => function ($model) {
                                        return $model->title ?? "";
                                    }
                                ],
                                [
                                    'label' => Yii::t('app', 'File Materi'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:240px;'],
                                    'value' => function ($model) {
                                        return $model->title ?? "";
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
                                    'contentOptions' => ['style' => 'width:200px;'],
                                    'header' => 'Action',
                                    'visibleButtons' => [
                                        'update' => false,
                                        'delete' => false,
                                        'view' => false,
                                    ],
                                    'template' => '{add}',
                                    'buttons' => array(
                                        'add' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-plus"></i> ', Url::to(['add-material', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-primary m-1 add-material', 'title' => Yii::t('app', 'Tambah Materi/ File'), 'data-key' => $key]);
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

function processData(type, code, title="", msg=""){
    let url;
    switch(type){
        case "delete-member":
            url= baseUrl+module+'/'+controller+'/delete-member'
            break;
        case "ban":
            url= baseUrl+module+'/'+controller+'/ban'
            break;
    }
    Swal.fire({
        title: title ?? messageConfirm,
        text: msg ?? textConfirm,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: textYes,
        cancelButtonText: textNo,
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        preConfirm: function (data) {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: url,
                    data: {
                        code: code,
                        _csrf: _csrf
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    success:function({status}){
                        resolve(status);
                    },
                    error:function(){
                        resolve(-1)
                    }
                });

            })
        },
    }).then(function ({isDismissed, value}) {
        if(isDismissed){
            Swal.fire(
                messageCanceled,
                textCanceled,
                'error'
            )
            return;
        }
        if(value==1){
            Swal.fire(
                messageSuccess,
                textSuccess,
                'success'
            ).then(function () {
                $.pjax.reload({container: '#p0', timeout: false});
            });
        }
        else if(value==-1){
            Swal.fire(
                messageFailed,
                textFailed,
                'error'
            ).then(function () {
                window.location.reload();
            });
        }
        else{
            Swal.fire(
                messageAnauthorized,
                textAnauthorized,
                'error'
            ).then(function () {
                window.location.reload();
            });
        }
    });
}

function init(){
    $('a#add-topic').click(function(e){
        e.preventDefault();
        $('#modalTitle').html($(this).data('title'));
        let url = $(this).attr('href');
        $.get(url, function(data) {
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    });
    $('a.add-material').click(function(e){
        e.preventDefault();
        let key = $(this).data('key');
        $('#modalTitle').html('Tambah Materi (File/ Video)')
        let url = $(this).attr('href');
        $.get(url, function(data) {
            data = "<div>"+data+"<span id='iskey' data-key="+key+">&nbsp;</span></div>";
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    });
    
    $('.btn-collapse').click(function(e){
        e.preventDefault();
        const key = $(this).data('key');
        let selector = $('.collapse-'+key)
        if($(selector).is(':visible')) {
            $('.collapse-' + key).hide('slow');
            $('i[data-key="'+key+'"]').removeClass('fa-arrow-up');
            $('i[data-key="'+key+'"]').addClass('fa-arrow-down');
        } else {
            $('.collapse-' + key).show('slow');
            $('i[data-key="'+key+'"]').removeClass('fa-arrow-down');
            $('i[data-key="'+key+'"]').addClass('fa-arrow-up');
        }
    });

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

    $('a.delete').click(function(e){
        e.preventDefault();
        processData("delete", $(this).data('id'), $(this).data('key'))
    });
};
// call function
init()
$('.lazy').Lazy()
$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>