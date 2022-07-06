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
                    src="<?= $model->image??Yii::$app->homeUrl."theme/stisla/assets/img/avatar/avatar-1.png" ?>"
                    class="rounded-circle profile-widget-picture" style="width:100px; height:100px">
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label tw-flex tw-justify-start tw-pl-4"> <span
                                data-toggle="tooltip" data-placement="right"
                                title="Nama Lengkap"><?= $model->nama??"-" ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-widget-description">
                <div class="profile-widget-name mb-4"> <span data-toggle="tooltip" data-placement="right"
                        title="NIS"><?= $model->nis??"-" ?></span>
                    <div class="text-muted d-inline font-weight-normal">
                        <div class="slash"></div> <span data-toggle="tooltip" data-placement="right"
                            title="NISN"><?= $model->nisn??"-" ?></span>
                    </div> <i class="fas fa-check-circle"></i>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="tw-font-bold"><i class="fas fa-venus-mars"></i> &nbsp;Jenis Kelamin</label>
                        <p><?= $model->jenis_kelamin='l'?"Laki-laki":"Perempuan" ?></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="tw-font-bold"><i class="fas fa-flag"></i> &nbsp;Tempat Lahir</label>
                        <p><?= $model->tempat_lahir??"-" ?></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="tw-font-bold"><i class="fas fa-calendar"></i> &nbsp;Tanggal Lahir</label>
                        <p><?= $model->tanggal_lahir?$model->tanggal_lahir:"-" ?></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="tw-font-bold"><i class="fas fa-cube"></i> &nbsp;Agama</label>
                        <p><?= $model->agama->nama??"-" ?></p>
                    </div>
                    <div class="col-12">
                        <label class="tw-font-bold"><i class="fas fa-map"></i> &nbsp;Alamat</label>
                        <p><?= $model->alamat_jalan??"-" ?></p>
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
                            <label class="tw-font-bold"><i class="fas fa-building"></i> &nbsp;Sekolah</label>
                            <p><?= $model->jenis_kelamin='l'?"Laki-laki":"Perempuan" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-graduation-cap"></i> &nbsp;Tahun Keluar</label>
                            <p
                                class="tw-bg-red-400 tw-py-1 tw-text-sm tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white">
                                <?= $model->tahun_lulus??"-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-id-card"></i> &nbsp;No Ujian</label>
                            <p><?= $model->nomor_peserta_ujian??"-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-check"></i> &nbsp;Keterangan</label>
                            <?php
                            switch($model->status_peserta_didik){
                                case "A":
                                    echo "<p class='tw-text-sm tw-bg-pink-400 tw-py-1 tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white'>Aktif</p>";
                                    break;
                                
                                case "P":
                                    echo "<p class='tw-text-sm tw-bg-purple-400 tw-py-1 tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white'>Pindah</p>";
                                    break;
                                
                                case "L":
                                    echo "<p class='tw-text-sm tw-bg-blue-400 tw-py-1 tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white'>Lulus</p>";
                                    break;
                                
                                case "N":
                                    echo "<p class='tw-bg-yellow-400 tw-py-px tw-px-4 tw-rounded-full tw-max-w-fit tw-text-white'>None</p>";
                                    break;
                                default:
                                    echo "-";
                                    break;
                            }
                            ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-file"></i> &nbsp;No Surat
                                Lulus</label>
                            <p><?= $model->no_seri_skhu??"-" ?></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <label class="tw-font-bold"><i class="fas fa-certificate"></i> &nbsp;No Ijazah</label>
                            <p><?= $model->no_seri_ijazah??"-" ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>