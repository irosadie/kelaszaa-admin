<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\{
    Pjax
};
use yii\grid\GridView;

$this->title = Yii::t('app', 'Detail Kelas');
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="row">
    <div class="col-12 col-lg-8 col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body pb-4 tw-space-y-4">
                <div class="tw-w-full tw-bg-slate-300 tw-flex tw-justify-center tw-h-80 tw-m-auto">
                    <img src="<?= $model->thumbnail ? $model->thumbnail : Yii::$app->setting->app('def_avt') ?>"
                        class="lazy tw-h-full tw-w-auto" />
                </div>
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
                <div class="tw-w-full mt-3">
                    <p class="tw-text-sm tw-font-bold tw-mb-1"><?= Yii::t('app', 'Deskripsi') ?>:</p>
                    <?= $model->desc ?? "-"; ?>
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
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', Yii::$app->request->get('fr') == 'm' ? Url::to(['member', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]) : 'index', ['class' => 'btn btn-sm btn-info m-1', 'data-pjax' => 1]);  ?>
                    <?= Html::a('<i class="fa fa-edit"></i> ', ['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)], ['data-pjax' => 1, 'class' => 'btn btn-sm btn-warning m-1']); ?>
                    <?= Html::button('<i class="fa fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1', 'id' => 'delete', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'data-title' => Yii::t('app', 'Hapus Kelas?'), 'data-desc' => Yii::t('app', 'Tindakan ini tidak bisa dibatalkan!')]) ?>
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
        case "delete":
            url= baseUrl+module+'/'+controller+'/delete'
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
                $.pjax({url:'index', container:'#p0', timeout: false});
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
    $('#delete').click(function(){
        processData("delete", $(this).attr('code'), $(this).data("title"), $(this).data("desc"));
    })
    $('.lazy').lazy();
}

init();

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>