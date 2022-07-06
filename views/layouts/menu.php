<?php

use app\utils\helper\Helper;
?>

<ul class="sidebar-menu">

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item active">
        <a href="<?= Yii::getAlias('@web/auth/dashboard') ?>" class="nav-link">
            <i class="fas fa-home"></i>
            <span><?= Yii::t('app', 'Dashboard') ?></span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="menu-header"><?= Yii::t('app', 'Main') ?></li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item">
        <a href="#" class="nav-link has-dropdown"><i
                class="fas fa-file"></i><span><?= Yii::t('app', 'Manajemen Kelas') ?></span></a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/juknis/create') ?>><?= Yii::t('app', 'Tambah Kelas') ?></a>
            </li>
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/juknis/index') ?>><?= Yii::t('app', 'Lihat Kelas') ?></a>
            </li>
        </ul>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item active">
        <a href="<?= Yii::getAlias('@web/auth/dashboard') ?>" class="nav-link">
            <i class="fas fa-home"></i>
            <span><?= Yii::t('app', 'Kelas Saya') ?></span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item">
        <a href="#" class="nav-link has-dropdown"><i
                class="fas fa-file"></i><span><?= Yii::t('app', 'Manajemen Member') ?></span></a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/juknis/create') ?>><?= Yii::t('app', 'Tambah Member') ?></a>
            </li>
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/juknis/index') ?>><?= Yii::t('app', 'Lihat Member') ?></a>
            </li>
        </ul>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item active">
        <a href="<?= Yii::getAlias('@web/auth/dashboard') ?>" class="nav-link">
            <i class="fas fa-home"></i>
            <span><?= Yii::t('app', 'Bank Materi') ?></span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item active">
        <a href="<?= Yii::getAlias('@web/auth/dashboard') ?>" class="nav-link">
            <i class="fas fa-home"></i>
            <span><?= Yii::t('app', 'E-Sertifikat') ?></span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item">
        <a href="#" class="nav-link has-dropdown"><i
                class="fas fa-file"></i><span><?= Yii::t('app', 'Jadwal Tatap Muka') ?></span></a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/juknis/create') ?>><?= Yii::t('app', 'Tambah Jadwal') ?></a>
            </li>
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/juknis/index') ?>><?= Yii::t('app', 'Lihat Jadwal') ?></a>
            </li>
        </ul>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item active">
        <a href="<?= Yii::getAlias('@web/auth/dashboard') ?>" class="nav-link">
            <i class="fas fa-home"></i>
            <span><?= Yii::t('app', 'Jadwal Tatap Muka') ?></span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="menu-header"><?= Yii::t('app', 'General') ?></li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <i class="fas fa-cogs"></i>
            <span><?= Yii::t('app', 'Data Master') ?></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/mentors/index') ?>><?= Yii::t('app', 'Mentor') ?></a>
            </li>
        </ul>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/users/index') ?>><?= Yii::t('app', 'Pengguna') ?></a>
            </li>
        </ul>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/payment-methods/index') ?>><?= Yii::t('app', 'Metode Bayar') ?></a>
            </li>
        </ul>
    </li>
    <?php endif; ?>

    <?php if (Yii::$app->users->can([])) : ?>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <i class="fas fa-cogs"></i>
            <span><?= Yii::t('app', 'Pengaturan') ?></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="nav-link"
                    href=<?= Yii::getAlias('@web/auth/setting-general/index') ?>><?= Yii::t('app', 'Pengaturan Umum') ?></a>
            </li>
        </ul>
    </li>
    <?php endif; ?>
</ul>