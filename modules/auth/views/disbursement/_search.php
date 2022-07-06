<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => true
    ],
]); ?>

<div class="input-group">
    <?= $form->field($model, 'date_begin')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'date_end')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'school_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'period_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'approve_status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'operator')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'amount')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'query', ['options' => ['tag' => false], 'errorOptions' => ['tag' => false]])->textInput(['placeholder' => 'Search'])->label(false) ?>
    <div class="input-group-btn">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary']) ?>
    </div>

</div>
<?php ActiveForm::end(); ?>
<?php
$js = <<< JS
$("body").on("beforeSubmit", "form#filter-masters", function (e) {
    var form = $(this);
    if (form.find(".has-error").length) 
    {
        return false;
    }
    return true;
});
JS;
$this->registerJs($js);
?>