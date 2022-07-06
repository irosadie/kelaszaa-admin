<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\{
    Pjax
};

$this->title = Yii::t('app', 'Detail Item RKAT');
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
                            IDR <?= number_format($model->amount_estimate, 0, '.', '.') ?>
                        </div>
                        <span
                            class="tw-text-xs tw-justify-end tw-flex"><?= Yii::t('app', "permintaan dana yang diajukan"); ?></span>
                    </div>
                    <span>
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Item RKAT') ?>:</p>
                        <p class='mt-2 mb-0'><?= ($model->juknisRelation->juknisItem->value ?? "") ?></p><span
                            class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= ($model->juknisRelation->juknisItem->parent->value ?? "-") ?></span>
                    </span>
                    <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Sekolah') ?>:</p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= $model->rkat->school->nama ?? "-" ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Status') ?>:</p>
                            <span
                                class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= $model->validations && $model->validation_level == 'treasurer' ? "disetujui" : "pengajuan" ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Riwayat Validasi Pengajuan') ?></h4>
            </div>
            <div class="card-body pb-4">
                <div class="table-responsive">
                    <table class="table table-striped table-striped table-md">
                        <tr>
                            <th style="width:64px;"><?= Yii::t('app', 'No') ?></th>
                            <th style="width:180px;"><?= Yii::t('app', 'Tanggal Validasi') ?></th>
                            <th style="width:180px;"><?= Yii::t('app', 'Peran') ?></th>
                            <th style="width:180px;"><?= Yii::t('app', 'Sebagai') ?></th>
                            <th><?= Yii::t('app', 'Nominal') ?></th>
                        </tr>
                        <?php $data = json_decode($model->validations, true); ?>
                        <?php if ($data) : ?>
                        <?php foreach ($data as $key => $value) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= Yii::$app->formatter->asDate($value['time'], 'dd/MM/YYYY') ?></td>
                            <td><?= $value['role'] ?? "" ?></td>
                            <td><?= $value['as'] ? ($dict[$value['as']] ?? "") : "-" ?></td>
                            <td><span class="tw-whitespace-nowrap">IDR
                                    <?= number_format($value['amount'], 0, '.', '.') ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <tr>
                            <td colspan="5" style="text-align:center;"><?= Yii::t('app', 'belum ada riwayat') ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <?php print_r($next); ?>
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
                            <td><?= $model->status == 1 ? "<span class='badge badge-success'>publish</span>" : "<span class='badge badge-warning'>draf</span>" ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <?= $model->amount_estimate > 0 && $canValidate ? Html::button('<i class="fas fa-check"></i> ', ['class' => 'btn btn-sm btn-success m-1 float-right', 'data-pjax' => 0, 'style' => 'color:#fff', 'id' => 'approve', 'data-key' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]) : "";  ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', Url::to(['view', 'code' => Yii::$app->encryptor->encodeUrl($model->rkat_id)]), ['class' => 'btn btn-sm btn-info m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
//register js variable
$this->registerJsVar("as", $next);
$js = <<< JS
function processData(type, code){
    let url;
    switch(type){
        case "approve":
            url= baseUrl+module+'/'+controller+'/approve'
            break;
    }
    swal({
        title: 'Approve?',
        text: "mohon teliti, tindakan ini tidak bisa dibatalkan!",
        type: 'warning',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off',
            id: "isvalid",
        },
        onOpen: function (el) {
            var container = $(el);
            container.find('#isvalid').maskMoney({ thousands:'.', decimal:',', affixesStay: false, precision: 0});
        },
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'No, cancel!',
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        inputValidator: (value) => {
            let val = parseInt(value.replaceAll('.', ''));
            return new Promise((resolve, reject) => {
                if(!val){
                    reject('mohon input nilai yang disetujui');
                }
                resolve();
            })
        },
        preConfirm: function (data) {
            let amount = parseInt(data.replaceAll('.', ''));
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: url,
                    data: {
                        code: code,
                        amount: amount,
                        as: as,
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
                'Approeved',
                'Penarikan berhasil di aproved :)',
                'success'
            ).then(function () {
                $.pjax.reload({container: '#p0', timeout: false})
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
                'Anda Tidak memiliki hak lagi',
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
    $('#approve').click(function(){
        processData('approve', $(this).data('key'));
    })
};
// call function
init()
$('.lazy').Lazy()

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>