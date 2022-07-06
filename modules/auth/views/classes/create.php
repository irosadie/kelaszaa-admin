<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = "Tambah RKAT";
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="create">
    <?= $this->render('_form', [
        'model' => $model,
        'year' => $year
    ]) ?>
</div>
<?php Pjax::end(); ?>