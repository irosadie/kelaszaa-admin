<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = "Juknis";
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="create">
    <?= $this->render('_form', [
        'model' => $model,
        'code' => $code
    ]) ?>
</div>
<?php Pjax::end(); ?>