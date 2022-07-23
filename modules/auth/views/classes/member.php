<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Daftar Member kelas');

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
                    <div class="card-header-action">
                        <!-- <?= $this->render('_search', ['model' => $searchModel, 'name' => 'query']) ?> -->
                        <?= Yii::$app->users->can([]) ? Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'member'), ['add-member', 'code' => Yii::$app->encryptor->encodeUrl($model->id)], ['class' => 'btn btn-info m-1', 'id' => 'add-member', 'data-title' => Yii::t('app', 'Tambah Member Ke Kelas')]) : "" ?>
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
                                    'label' => Yii::t('app', 'Nama'),
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->member->full_name ?? "";
                                    }
                                ],
                                [
                                    'label' => Yii::t('app', 'Email'),
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->member->email ?? "";
                                    }
                                ],
                                [
                                    'label' => Yii::t('app', 'Nomor Booking'),
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->booking_id ? "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . $model->booking->code . "</span>" : "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'tidak ada') . "</span>";
                                    }
                                ],
                                [
                                    'label' => Yii::t('app', 'Tanggal Gebung'),
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->created_at ? date('Y-m-d', $model->created_at) : "-";
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => Yii::t('app', 'Status'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        if (!$model->is_blocked) :
                                            if ($model->is_alumni) :
                                                return "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'alumni') . "</span>";
                                            endif;
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
                                        endif;
                                        return "<span class='tw-bg-red-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'diblokir') . "</span>";
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
                                                            ' . Html::a(Yii::t('app', 'detail*'), Url::to(['detail-member', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'dropdown-item tw-whitespace-nowrap']) . '
                                                            ' . Html::a($model->is_blocked ? Yii::t('app', 'buka blokir*') : Yii::t('app', 'blokir*'), '', ['class' => 'ban dropdown-item tw-whitespace-nowrap', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'data-title' => $model->is_blocked ? Yii::t('app', 'Buka Blokir?') : Yii::t('app', 'Blokir Member dari Kelas?'), 'data-desc' => Yii::t('app', 'Lanjutkan jika yakin!')]) . '
                                                            ' . Html::a(Yii::t('app', 'delete*'), '', ['class' => 'delete dropdown-item tw-whitespace-nowrap', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'data-title' => Yii::t('app', 'Hapus Member dari Kelas?'), 'data-desc' => Yii::t('app', 'Tindakan ini tidak bisa dibatalkan!')]) . '
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

$('.delete').click(function(){
    processData("delete-member", $(this).attr('code'), $(this).data("title"), $(this).data("desc"));
})
$('.ban').click(function(){
    processData("ban", $(this).attr('code'), $(this).data("title"), $(this).data("desc"));
})

$('a#add-member').click(function(e){
    e.preventDefault();
    $('#modalTitle').html($(this).data('title'));
    let url = $(this).attr('href');
    $.get(url, function(data) {
        $('#modal').modal('show').find('#modalContent').html(data)
    });
});
$('.lazy').Lazy()
$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>