<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="row mt-sm-4">
    <div class="col-12 col-md-12 col-lg-5">
        <div class="card profile-widget">
            <div class="profile-widget-header">
                <img alt="image"
                    src="<?= $model->photo??Yii::$app->homeUrl."theme/stisla/assets/img/avatar/avatar-1.png" ?>"
                    class="rounded-circle profile-widget-picture" style="width:100px; height:100px">
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label tw-flex tw-justify-start tw-pl-4">
                            <span data-toggle="tooltip" data-placement="right"
                                title="Username"><?= $model->username??"-" ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-widget-description">
                <div class="profile-widget-name"> <span data-toggle="tooltip" data-placement="right"
                        title="Name"><?= $model->full_name??"-" ?></span>
                    <div class="text-muted d-inline font-weight-normal">
                        <div class="slash"></div> <span data-toggle="tooltip" data-placement="right"
                            title="Role"><?= $model->role ?></span>
                    </div> <i class="fas fa-check-circle"></i>
                </div>
                <p><?php // $model->biography?(strlen($model->biography)>=96?substr_replace($model->biography, "...", 100):$model->biography):"Belum ada bio" ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
            <div class="needs-validation">
                <div class="card-header">
                    <h4>Data Kamu</h4>
                </div>
                <div class="card-body" style="margin-top:20px">

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-envelope"></i> &nbsp;Email</label>
                            <p><?= $model->email??"-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-phone"></i> &nbsp;Phone</label>
                            <p>
                                <?= $model->phone??"-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-check"></i> &nbsp;Status</label>
                            <p
                                class="tw-bg-blue-400  tw-py-1 tw-text-sm tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white">
                                <?= $model->status==10?"Aktif":"Tidak Aktif"?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-map"></i> &nbsp;Address</label>
                            <p><?= $model->address??"-" ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right" style="margin-top:-40px">
                    <?= Html::a('<i class="fa fa-key"></i> Password', Url::to('change-password'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-primary m-1']); ?>
                    <?= Html::a('<i class="fa fa-camera"></i> Photo Profile', Url::to('upload-photo'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-warning m-1']); ?>
                    <?= Html::a('<i class="fa fa-edit"></i> Ubah Data', Url::to('update'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-success m-1']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>