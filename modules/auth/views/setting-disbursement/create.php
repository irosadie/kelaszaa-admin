<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = Yii::t('app', "Pengaturan Pencairan Dana");
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="create">
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>
<?php Pjax::end(); ?>