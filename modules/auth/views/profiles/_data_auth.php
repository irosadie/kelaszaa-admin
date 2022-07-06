<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
?>

<div class="row mt-sm-4">
    <div class="col-12 col-md-12 col-lg-5 tw-pb-0">
        <div class="card profile-widget tw-min-h-full -tw-mt-8">
            <div class="profile-widget-header">
                <img alt="image"
                    src="<?= $model->photo??Yii::$app->homeUrl."theme/stisla/assets/img/avatar/avatar-1.png" ?>"
                    class="rounded-circle profile-widget-picture" style="width:100px; height:100px">
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label tw-flex tw-justify-start tw-pl-4">
                            <span data-toggle="tooltip" data-placement="right"
                                title="Nama Lengkap"><?= $model->full_name??"-" ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-widget-description">
                <div class="profile-widget-name mb-4"> <span data-toggle="tooltip" data-placement="right"
                        title="Username"><?= $model->username??"-" ?></span>
                    <div class="text-muted d-inline font-weight-normal">
                        <div class="slash"></div> <span data-toggle="tooltip" data-placement="right"
                            title="type account"><?= $accountType??"-" ?></span>
                    </div>
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label class="tw-font-bold"><i class="fas fa-map"></i> &nbsp;Alamat</label>
                        <p><?= $model->address??"-" ?></p>
                    </div>
                    <div class="col-12">
                        <label class="tw-font-bold"><i class="fas fa-edit"></i> &nbsp;Biografy</label>
                        <p><?= $model->biography?(strlen($model->biography)>=96?substr_replace($model->biography, "...", 100):$model->biography):"Belum ada bio" ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
            <div class="needs-validation">
                <div class="card-header">
                    <h4>Data Kelulusan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-graduation-cap"></i> &nbsp;Tahun Keluar</label>
                            <p
                                class="tw-bg-red-400 tw-py-1 tw-text-sm tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white">
                                <?= $accountType =='single' ? ($model->year_of_graduate??"-"):"*lihat pada tab smart" ?>
                            </p>
                        </div>
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
                                class="tw-bg-blue-400 tw-py-1 tw-text-sm tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white">
                                <?= $model->status==10?"Aktif":"Tidak Aktif"?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right" style="margin-top:-40px">
                    <?= $accountType=='single'?Html::a('<i class="fa fa-edit"></i> Ubah Tahun Lulus', Url::to('form-year'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-info m-1 btn-add-yog']):''; ?>
                    <?= Html::a('<i class="fa fa-key"></i> Password', Url::to('change-password'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-primary m-1']); ?>
                    <?= Html::a('<i class="fa fa-camera"></i> Photo Profile', Url::to('upload-photo'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-warning m-1']); ?>
                    <?= Html::a('<i class="fa fa-edit"></i> Ubah Data', Url::to('update'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-success m-1']); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-6">
        <div class="card">
            <div class="needs-validation">
                <div class="card-header">
                    <h4>Riwayat Pendidikan</h4>
                </div>
                <div class="card-body" style="margin-top:20px">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-envelope-open"></i> &nbsp;Email</label>
                            <p><?= $model->email??"-" ?></p>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-phone"></i> &nbsp;Phone</label>
                            <p><?= $model->phone??"-" ?></p>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-check"></i> &nbsp;Status</label>
                            <p><?= $model->status==10?"Active":($model->status==9?"Unvalidate":($model->status==0?"Banned":"Deleted")) ?>
                            </p>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-calendar-alt"></i> &nbsp;Registered At</label>
                            <p><?= "makan" ?></p>
                        </div>
                        <div class="form-group col-12" style="margin-top:-30px">
                            <label><i class="fas fa-map-marker-alt"></i> &nbsp;Address</label>
                            <p><?= $model->address??"-" ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right" style="margin-top:-40px">
                    <?= Html::a('<i class="fa fa-key"></i> Password', Url::to(Yii::$app->homeUrl.'administrator/profiles/change-password'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-primary m-1']); ?>
                    <?= Html::a('<i class="fa fa-camera"></i> Photo Profile', Url::to(Yii::$app->homeUrl.'administrator/profiles/upload-photo'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-warning m-1']); ?>
                    <?= Html::a('<i class="fa fa-edit"></i> Ubah Data', Url::to(Yii::$app->homeUrl.'administrator/profiles/update'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-success m-1']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="needs-validation">
                <div class="card-header">
                    <h4>Riwayat Pekerjaan</h4>
                </div>
                <div class="card-body" style="margin-top:20px">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-envelope-open"></i> &nbsp;Email</label>
                            <p><?= $model->email??"-" ?></p>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-phone"></i> &nbsp;Phone</label>
                            <p><?= $model->phone??"-" ?></p>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-check"></i> &nbsp;Status</label>
                            <p><?= $model->status==10?"Active":($model->status==9?"Unvalidate":($model->status==0?"Banned":"Deleted")) ?>
                            </p>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" style="margin-top:-30px">
                            <label><i class="fas fa-calendar-alt"></i> &nbsp;Registered At</label>
                            <p><?= "makan" ?></p>
                        </div>
                        <div class="form-group col-12" style="margin-top:-30px">
                            <label><i class="fas fa-map-marker-alt"></i> &nbsp;Address</label>
                            <p><?= $model->address??"-" ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right" style="margin-top:-40px">
                    <?= Html::a('<i class="fa fa-key"></i> Password', Url::to(Yii::$app->homeUrl.'administrator/profiles/change-password'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-primary m-1']); ?>
                    <?= Html::a('<i class="fa fa-camera"></i> Photo Profile', Url::to(Yii::$app->homeUrl.'administrator/profiles/upload-photo'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-warning m-1']); ?>
                    <?= Html::a('<i class="fa fa-edit"></i> Ubah Data', Url::to(Yii::$app->homeUrl.'administrator/profiles/update'), ['data-pjax' => 1, 'class' => 'btn btn-sm btn-success m-1']); ?>
                </div>
            </div>
        </div>
    </div> -->
</div>