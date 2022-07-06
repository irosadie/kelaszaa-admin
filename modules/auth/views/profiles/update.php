<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
$this->title = 'Change Your Data';
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
<?php Pjax::end(); ?>