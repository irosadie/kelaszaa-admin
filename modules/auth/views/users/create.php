<?php

use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Tambah Mentor');
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
<?php Pjax::end(); ?>