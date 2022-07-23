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
                <img alt="image"
                    src="<?= ($model->member->avatar ? $model->member->avatar : Yii::$app->setting->app('def_avt')) ?>"
                    class="rounded-circle profile-widget-picture tw-w-24 tw-h-24">
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label tw-flex tw-justify-start tw-pl-4">
                            <span data-toggle="tooltip" data-placement="right"
                                title=<?= Yii::t('app', 'Username') ?>><?= $model->member->username ?? $model->member->email ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-widget-description">
                <div class="profile-widget-name"> <span data-toggle="tooltip" data-placement="right"
                        title=<?= Yii::t('app', 'Name') ?>><?= $model->member->full_name ?? "-" ?></span>
                    <div class="text-muted d-inline font-weight-normal">
                        <div class="slash"></div> <span data-toggle="tooltip" data-placement="right"
                            title=<?= Yii::t('app', 'Gender') ?>><?= ($model->member->gender == 1 ? Yii::t('app', 'Laki-laki') : Yii::t('app', 'Perempuan')) ?></span>
                    </div> <i class="fas fa-check-circle"></i>
                </div>
                <p><?= $model->member->biography ? (strlen($model->member->biography) >= 96 ? substr_replace($model->member->biography, "...", 100) : $model->member->biography) : "Belum ada bio" ?>
                </p>
            </div>
        </div>

        <div class="card">
            <div class="needs-validation">
                <div class="card-header">
                    <h4><?= Yii::t('app', 'Detail Member') ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-calendar"></i>
                                <?= Yii::t('app', 'Tanggal Bergabung') ?></label>
                            <p><?= $model->created_at ? date('Y-m-d', $model->created_at) : "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-book"></i>
                                <?= Yii::t('app', 'Nomor Booking') ?></label>
                            <p><?= $model->booking_id ? "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . $model->booking->code . "</span>" : "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'tidak ada') . "</span>"; ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-money-bill"></i>
                                <?= Yii::t('app', 'Harga Beli Kelas') ?></label>
                            <p><?= $model->booking_id ? "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>Rp." . number_format($model->booking->price_paid, 0, '.', '.') . "</span>" : "Rp.<span title='member ini diinput secara manual, harga beli tidak terdefinisi'>********</span>"; ?>
                            </p>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-check"></i>
                                <?= Yii::t('app', 'Status Member') ?></label>
                            <p>
                                <?php
                                if (!$model->is_blocked) :
                                    if ($model->is_alumni) :
                                        echo "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'alumni') . "</span>";
                                    else :
                                        switch ($model->status):
                                            case 1:
                                                echo "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'aktif') . "</span>";
                                                break;
                                            case 0:
                                                echo "<span class='tw-bg-red-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'tidak aktif') . "</span>";
                                                break;
                                            default:
                                                echo "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'lainnya') . "</span>";
                                                break;
                                        endswitch;
                                    endif;
                                else :
                                    echo "<span class='tw-bg-red-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>" . Yii::t('app', 'diblokir') . "</span>";
                                endif;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
            <div class="needs-validation">
                <div class="card-header">
                    <h4><?= Yii::t('app', 'Detail Data Pengguna') ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-map-marker-alt"></i>
                                <?= Yii::t('app', 'Tempat Lahir') ?></label>
                            <p><?= $model->member->born_in ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-calendar"></i>
                                <?= Yii::t('app', 'Tanggal Lahir') ?></label>
                            <p><?= $model->member->born_at ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-envelope"></i>
                                <?= Yii::t('app', 'Email') ?></label>
                            <p><?= $model->member->email ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-phone"></i>
                                <?= Yii::t('app', 'Phone') ?></label>
                            <p><?= $model->member->phone ?? "-" ?></p>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-building"></i>
                                <?= Yii::t('app', 'Agency') ?></label>
                            <p><?= $model->member->agency ?? "-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-map"></i>
                                <?= Yii::t('app', 'Address') ?></label>
                            <p><?= $model->member->address ?? "-" ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', ['member', 'code' => Yii::$app->encryptor->encodeUrl($model->class->id)], ['class' => 'btn btn-sm btn-info m-1', 'data-pjax' => 1]);  ?>
                    <?= Html::button($model->is_blocked ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-ban"></i> ', ['class' => 'btn btn-sm btn-warning m-1', 'id' => 'ban', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1, 'data-title' => $model->is_blocked ? Yii::t('app', 'Buka Blokir?') : Yii::t('app', 'Blokir Member dari Kelas?'), 'data-desc' => Yii::t('app', 'Lanjutkan jika yakin!')]); ?>
                    <?= $model->is_blocked ? '' : Html::button($model->is_alumni ? '<i class="fa fa-power-off"></i> ' : '<i class="fa fa-graduation-cap"></i> ', ['class' => 'btn btn-sm btn-primary m-1', 'id' => 'alumni', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1, 'data-title' => $model->is_alumni ? Yii::t('app', 'Batalkan Alumni?') : Yii::t('app', 'Jadikan Alumni?'), 'data-desc' => Yii::t('app', 'Lanjutkan jika yakin!')]); ?>
                    <?= Html::button('<i class="fa fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1', 'id' => 'delete', 'code' => Yii::$app->encryptor->encodeUrl($model->id), 'data-title' => Yii::t('app', 'Hapus Member dari Kelas?'), 'data-desc' => Yii::t('app', 'Tindakan ini tidak bisa dibatalkan!')]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsVar('parent', Yii::$app->encryptor->encodeUrl($model->class->id));
$js = <<< JS
function processData(type, code, title="", msg=""){
    let url;
    switch(type){
        case "delete":
            url= baseUrl+module+'/'+controller+'/delete-member'
            break;
        case "ban":
            url= baseUrl+module+'/'+controller+'/ban'
            break;
        case "alumni":
            url= baseUrl+module+'/'+controller+'/alumni'
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
                if(type=='delete-member'){
                    $.pjax({url:'member?code='+parent, container:'#p0', timeout: false});
                }
                else{
                    $.pjax.reload({container: '#p0', timeout: false});
                }
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

$('#delete').click(function(){
    processData("delete", $(this).attr('code'), $(this).data("title"), $(this).data("desc"));
})
$('#ban').click(function(){
    processData("ban", $(this).attr('code'), $(this).data("title"), $(this).data("desc"));
})
$('#alumni').click(function(){
    processData("alumni", $(this).attr('code'), $(this).data("title"), $(this).data("desc"));
})
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>