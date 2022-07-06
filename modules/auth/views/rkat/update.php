<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = "Ubah RKAT";
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="update">
    <?= $this->render('_form', [
        'model' => $model,
        'schools' => $schools,
        'juknis' => $juknis,
        'year' => $year
    ]) ?>
</div>
<?php Pjax::end(); ?>