<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\{
    Pjax
};
use yii\grid\GridView;

$this->title = Yii::t('app', 'Detail RKAT');
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="row">
    <div class="col-12 col-lg-8 col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body pb-4">
                <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                    <div class="w-full">
                        <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Kode') ?>:</p>
                        <p
                            class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>
                            <?= $model->code ?? "-" ?></p>
                    </div>
                    <div class="tw-w-full">
                        <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Nama') ?>:</p>
                        <?= $model->name ?? "" ?>
                    </div>
                    <div class="tw-w-full">
                        <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Tipe Transaksi') ?>:</p>
                        <p
                            class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>
                            <?= $model->type ?? "-" ?></p>
                    </div>
                </div>
                <div class="tw-w-full mt-3">
                    <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Alur Pembayaran') ?>:</p>
                    <?= $model->paying_guide ?? "-"; ?>
                </div>
                <div class="tw-w-full mt-3">
                    <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Data') ?>:</p>
                    <?= $model->data ?? "-"; ?>
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
                            <td><?= $model->status == 1 ? "<span class='badge badge-success'>Publish</span>" : "<span class='badge badge-primary'>Draft</span>" ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <?= Yii::$app->users->can([]) ? Html::button('<i class="fas fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1 float-right', 'data-pjax' => 0, 'style' => 'color:#fff', 'id' => 'delete', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]) : "";  ?>
                    <?= Yii::$app->users->can([]) ? Html::a('<i class="fas fa-edit"></i> ', Url::to(['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-warning m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]) : "";  ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', 'index', ['class' => 'btn btn-sm btn-info m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsVar("is_code", Yii::$app->encryptor->encodeUrl($model->id));

$js = <<< JS
function processData(type, code){
    let url;
    switch(type){
        case "delete":
            url= baseUrl+module+'/'+controller+'/delete'
            break;
    }
    swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
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
                    success:function(result){
                        resolve(result.status);
                    },
                });

            })
        },
    }).then(function (data) {
        if(data==1){
            swal(
                'Delete Success',
                'Data berhasil di hapus :)',
                'success'
            ).then(function () {
                $.pjax({url:'index', container:'#p0', timeout: false});
            });
        }
        else if(data==-1){
            swal(
                'Oups Galat!!!',
                'Sepertinya ada yang salah, coba ulangi',
                'error'
            ).then(function () {
                window.location.reload();
            });
        }
        else{
            swal(
                'Ups!!!',
                'Anda Tidak memiliki hak untuk menghapus lagi',
                'error'
            ).then(function () {
                window.location.reload();
            });
        }
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            swal(
            'Cancelled',
            'Your imaginary file is safe :)',
            'error'
            )
        }
    });
}
function init(){
    $('#delete').click(function(){
        processData("delete", $(this).attr('data'))
    });
};
init()
$('.lazy').Lazy()
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>