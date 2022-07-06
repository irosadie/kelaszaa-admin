<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = "Ubah Item Juknis";
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="update">
    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents
    ]) ?>
</div>
<?php Pjax::end(); ?>