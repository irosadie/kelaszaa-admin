<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="row mt-sm-4">
    <div class="col-12 col-md-12 col-lg-5">
        <div class="card profile-widget">
            <div class="profile-widget-header">
                <img alt="image" src="<?= ($model->avatar ? $model->avatar : Yii::$app->setting->app('def_avt')) ?>"
                    class="rounded-circle profile-widget-picture tw-w-24 tw-h-24">
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label tw-flex tw-justify-start tw-pl-4">
                            <span data-toggle="tooltip" data-placement="right"
                                title=<?= Yii::t('app', 'Username') ?>><?= $model->username ?? $model->email ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-widget-description">
                <div class="profile-widget-name"> <span data-toggle="tooltip" data-placement="right"
                        title=<?= Yii::t('app', 'Name') ?>><?= $model->full_name ?? "-" ?></span>
                    <div class="text-muted d-inline font-weight-normal">
                        <div class="slash"></div> <span data-toggle="tooltip" data-placement="right"
                            title=<?= Yii::t('app', 'Gender') ?>><?= ($model->gender == 1 ? Yii::t('app', 'Laki-laki') : Yii::t('app', 'Perempuan')) ?></span>
                    </div> <i class="fas fa-check-circle"></i>
                </div>
                <p><?= $model->biography ? (strlen($model->biography) >= 96 ? substr_replace($model->biography, "...", 100) : $model->biography) : "Belum ada bio" ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
            <div class="needs-validation">
                <div class="card-header">
                    <h4><?= Yii::t('app', 'Detail Data Mentor') ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-map-marker-alt"></i>
                                <?= Yii::t('app', 'Tempat Lahir') ?></label>
                            <p><?= $model->born_in ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-calendar"></i>
                                <?= Yii::t('app', 'Tanggal Lahir') ?></label>
                            <p><?= $model->born_at ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-envelope"></i>
                                <?= Yii::t('app', 'Email') ?></label>
                            <p><?= $model->email ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-phone"></i>
                                <?= Yii::t('app', 'Phone') ?></label>
                            <p><?= $model->phone ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-check"></i>
                                <?= Yii::t('app', 'Status') ?></label>
                            <p
                                class='tw-bg-blue-400 tw-w-fit tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>
                                <?= Yii::t('app', 'aktif') ?></p>

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-map"></i>
                                <?= Yii::t('app', 'Address') ?></label>
                            <p><?= $model->address ?? "-" ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', 'index', ['class' => 'btn btn-sm btn-info m-1', 'data-pjax' => 1]);  ?>
                    <?= Html::button('<i class="fa fa-key"></i> ', ['class' => 'btn btn-sm btn-primary m-1', 'id' => 'reset-password', 'data-title' => Yii::t('app', 'Reset Password?'), 'data-desc' => Yii::t('app', 'Akan mengirim email reset password ke akun Mentor')]) ?>
                    <?= Html::a('<i class="fa fa-camera"></i> ', ['upload-photo', 'code' => Yii::$app->encryptor->encodeUrl($model->id)], ['data-pjax' => 1, 'class' => 'btn btn-sm btn-success m-1']); ?>
                    <?= Html::a('<i class="fa fa-edit"></i> ', ['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)], ['data-pjax' => 1, 'class' => 'btn btn-sm btn-warning m-1']); ?>
                    <?= Html::button('<i class="fa fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1', 'id' => 'delete', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'data-title' => Yii::t('app', 'Hapus Mentor?'), 'data-desc' => Yii::t('app', 'Tindakan ini tidak bisa dibatalkan!')]) ?>
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
        case "reset":
            url= baseUrl+module+'/'+controller+'/reset-password'
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
    $('#reset-password').click(function(){
        processData("delete-password", $(this).attr('code'), $(this).data("title"), $(this).data("desc"));
    })
}
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>