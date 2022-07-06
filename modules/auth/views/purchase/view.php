<?php

use yii\helpers\{
    Html,
};
use yii\widgets\{
    Pjax
};

$this->title = Yii::t('app', 'Detail Barang/ Item');
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
                            IDR <?= number_format($model->amount_total, 0, '.', '.') ?>
                        </div>
                        <span
                            class="tw-text-xs tw-justify-end tw-flex"><?= Yii::t('app', "dana yang dibelanjakan"); ?></span>
                    </div>
                    <span>
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Item') ?>:</p>
                        <p class="tw-text-sm"><?= $model->item_name ?></p>
                    </span>
                    <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Tanggal') ?>:</p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= $model->date ? date('Y-m-d', strtotime($model->date)) : "-" ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Jumlah') ?>:</p>
                            <?= ($model->item_total ?? "-") . ($model->unit_str ? ", " . $model->unit_str : "") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Detail Toko/ Suplier') ?></h4>
            </div>
            <div class="card-body pb-4">
                <div class="tw-grid tw-space-y-3 mb-4">
                    <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Nama') ?>:</p>
                            <?= $model->store_name ?? "-" ?>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Handphone/ Telp') ?>:</p>
                            <?= $model->store_phone ?? "-" ?>
                        </div>
                    </div>
                </div>
                <span>
                    <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Alamat') ?>:</p>
                    <p class="tw-text-sm"><?= $model->store_address ?></p>
                </span>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Kwitansi dan Bukti Pembelian') ?></h4>
                <div class="card-header-action tw-flex">
                    <?= Html::button('<i class="fas fa-money-bill"></i> ' . Yii::t('app', 'Kwitansi'), ['id' => 'receipt', 'class' => 'mr-4 btn btn-primary tw- tw-whitespace-nowrap']) ?>
                    <?= Html::button('<i class="fas fa-image"></i> ' . Yii::t('app', 'Foto Barang'), ['id' => 'photo', 'class' => 'btn btn-light tw- tw-whitespace-nowrap']) ?>
                </div>
            </div>
            <div class="card-body pb-4">
                <div class="tw-grid tw-space-y-3 mb-4">
                    <div class="tw-w-full tw-bg-slate-300 tw-flex tw-justify-center">
                        <?php if ($model->proof_of_payments) : ?>
                        <img id="receipt-content" src="<?= $model->proof_of_payments ?>"
                            class="lazy tw-w-full tw-h-auto" style="display:block" />
                        <?php else : ?>
                        <p id="receipt-content" class="mt-3"><?= Yii::t('app', 'Bukti pembayaran tidak diupload') ?></p>
                        <?php endif; ?>
                        <?php if ($model->photos) : ?>
                        <img id="photo-content" src="<?= $model->photos ?>" class="lazy tw-w-full tw-h-auto"
                            style="display:none" />
                        <?php else : ?>
                        <p id="receipt-content" class="mt-3"><?= Yii::t('app', 'Foto barang tidak diupload') ?></p>
                        <?php endif; ?>
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
                            <td><?= $model->status == 1 ? "<span class='badge badge-success'>publish</span>" : "<span class='badge badge-warning'>draf</span>" ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <!-- lebih sehari engga bisa didelete -->
                    <?= (time() - $model->created_at >= 36001) ? "" : Html::button('<i class="fas fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1 float-right', 'data-pjax' => 0, 'style' => 'color:#fff', 'id' => 'delete', 'data-key' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]);  ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', 'index', ['class' => 'btn btn-sm btn-info m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsVar('max_disbursement', 0);
$js = <<< JS
function processData(type, code){
    let url;
    switch(type){
        case "delete":
            url= baseUrl+module+'/'+controller+'/delete'
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
    $('#photo').click(function(){
        $(this).removeClass('btn-light').addClass('btn-primary');
        $('#receipt').removeClass('btn-primary').addClass('btn-light');
        $('#receipt-content').hide();
        $('#photo-content').show('slow');
    });
    $('#receipt').click(function(){
        $(this).removeClass('btn-light').addClass('btn-primary');
        $('#photo').removeClass('btn-primary').addClass('btn-light');
        $('#photo-content').hide();
        $('#receipt-content').show('slow');
    });
    $('#delete').click(function(){
        processData('delete', $(this).data('key'));
    })

};
// call function
init()
$('.lazy').Lazy()

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>