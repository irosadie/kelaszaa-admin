<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\{
    Pjax
};

$this->title = 'Detail Pencairan Dana';
?>
<?php Pjax::begin(['id' => 'p0']); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="row">
    <div class="col-12 col-lg-8 col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body pb-4">
                <div class="tw-grid tw-space-y-3 tw-mt-3">
                    <div>
                        <div class="tw-bg-slate-200 tw-text-4xl tw-py-8 tw-text-center">
                            IDR <?= number_format($model->amount_request, 0, '.', '.') ?>
                        </div>
                        <span
                            class="tw-text-xs tw-justify-end tw-flex"><?= Yii::t('app', "total permintaan pencairan dana &nbsp;<strong>{$percentage}%</strong>&nbsp; dari total dana bisa ditarik"); ?></span>
                    </div>
                    <span>
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Item RKAT') ?>:</p>
                        <p class="tw-text-sm"><?= $model->desc ?></p>
                    </span>
                    <span>
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Deskripsi Pencairan') ?>:
                        </p>
                        <p class="tw-text-sm"><?= $model->desc ?></p>
                    </span>
                    <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Sekolah') ?>:</p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= $model->rkatItem->rkat->school->nama ?? "-" ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Tahun Anggaran') ?>:</p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= $model->rkatItem->rkat->year ?? "-" ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Biaya RKAT disetujui') ?>:
                            </p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-whitespace-nowrap tw-rounded-full tw-text-white tw-text-xs'>IDR
                                <?= number_format($model->rkatItem->amount_estimate ?? 0, 0, '.', '.') ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Periode Penarikan') ?>:</p>
                            <span><?= $model->disbursementPlan->name ?? "-" ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Dana Bisa Ditarik') ?>:
                            </p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1  tw-whitespace-nowrap tw-rounded-full tw-text-white tw-text-xs'>IDR
                                <?= number_format($model->rkatItem->getRemainingFunds(true), 0, '.', '.') ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Dana yang Disetujui') ?>:
                            </p>
                            <span class='tw-bg-red-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>
                                <?= $model->amount_approved ? "IDR " . number_format($model->amount_approved ?? 0, 0, '.', '.') : Yii::t('app', 'belum ada') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped ">
                        <tr>
                            <td><strong><?= Yii::t('app', 'Tanggal dibuat') ?></strong></td>
                            <td>:</td>
                            <td><?= date("d/m/Y h:m:s", $model->created_at) ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Dibuat oleh') ?></strong></td>
                            <td>:</td>
                            <td><?= $model->createdBy->full_name ?? "-" ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Status') ?></strong></td>
                            <td>:</td>
                            <td><?= $model->validations && $model->validation_level == 'treasurer' ? "<span class='badge badge-success'>disetujui</span>" : "<span class='badge badge-warning'>pengajuan</span>" ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Tanggal disetujui') ?></strong></td>
                            <td>:</td>
                            <td>
                                <?php
                                $json = json_decode($model->validations);
                                $approved_at = "-";
                                if ($json->time ?? 0) {
                                    $approved_at = $json->time;
                                }
                                ?>
                                <?= $model->validations && $model->validation_level == 'treasurer' ? date("d/m/Y h:m:s", $approved_at) : "belum disetujui" ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Disetujui oleh') ?></strong></td>
                            <td>:</td>
                            <td>
                                <?php
                                $json = json_decode($model->validations);
                                $full_name = "-";
                                if ($json->full_name ?? 0) {
                                    $full_name = $json->full_name;
                                }
                                ?>
                                <?= $model->validations && $model->validation_level == 'treasurer' ? $full_name : "belum disetujui" ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <?= (!$model->validation_level == 'treasurer') && (Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], [$model->rkatItem->rkat->school_id])) ? Html::button('<i class="fas fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1 float-right', 'data-pjax' => 0, 'style' => 'color:#fff', 'id' => 'delete', 'data-key' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]) : "";  ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', ['view', 'code' => Yii::$app->encryptor->encodeUrl($model->rkatItem->rkat->id)], ['class' => 'btn btn-sm btn-info m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsVar('max_disbursement', $max_disbursement);
$this->registerJsVar('parent_code', Yii::$app->encryptor->encodeUrl($model->rkatItem->rkat->id));
$js = <<< JS
function deleteData(type, code){
    let url;
    switch(type){
        case "delete":
            url= baseUrl+module+'/'+controller+'/item-delete'
            break;
    }
    swal({
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
                $.pjax({url:'view?code='+parent_code, container:'#p0', timeout: false});
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
        deleteData('delete', $(this).data('key'));
    })

};
// call function
init()
$('.lazy').Lazy()

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>