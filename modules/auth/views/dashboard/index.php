<?php

use yii\helpers\Url;
?>
<div class="hero text-white hero-bg-image hero-bg-parallax"
    style="background-image: url('<?= Yii::$app->homeUrl ?>theme/stisla/assets/img/unsplash/andre-benz-1214056-unsplash.jpg');">
    <div class="hero-inner">
        <h2><?= Yii::$app->setting->app('app_name') ?></h2>
        <p class="lead">
            <?= Yii::t('app', 'Gapailah cita-cita setinggi langit, jika jatuh, kau berada diantara bintang-bintang!') ?>
        </p>
        <div class="mt-4">
            <?php if (Yii::$app->users->can([])) : ?>
            <a href="<?= Url::to('@web/auth/profiles/me') ?>"
                class="m-2 btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-user"></i>
                <?= Yii::t('app', 'Profile ') ?></a>
            <?php endif; ?>
            &nbsp;
            <?php if (Yii::$app->users->can(["operator", "person_responsible", "school_treasurer", "headmaster", "treasure"])) : ?>
            <a href="<?= Url::to('@web/auth/purchase/index') ?>"
                class="m-2 btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-file-pdf"></i>
                <?= Yii::t('app', 'Laporan Belanja ') ?></a>
            <?php endif; ?>
            &nbsp;
            <?php if (Yii::$app->users->can(["operator", "treasurer"])) : ?>
            <a href="<?= Url::to('@web/auth/disbursement/index') ?>"
                class="m-2 btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-tasks"></i>
                <?= Yii::t('app', 'Pengajuan Penarikan Dana') ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>