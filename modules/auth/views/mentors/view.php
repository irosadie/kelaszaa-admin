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
                <img alt="image" src="<?= $model->avatar ?? Yii::$app->setting->app('def_avt') ?>"
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
                    <?= Html::a('<i class="fa fa-camera"></i> ' . Yii::t('app', 'upload foto'), ['upload-photo', 'code' => Yii::$app->encryptor->encodeUrl($model->id)], ['data-pjax' => 1, 'class' => 'btn btn-sm btn-warning m-1']); ?>
                    <?= Html::a('<i class="fa fa-edit"></i> ' . Yii::t('app', 'ubah data'), ['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)], ['data-pjax' => 1, 'class' => 'btn btn-sm btn-success m-1']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>