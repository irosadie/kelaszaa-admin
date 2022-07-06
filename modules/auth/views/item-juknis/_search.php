<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'id' => 'filter-category',
    'method' => 'get',
    'options' => [
        'data-pjax' => true
    ],
]); ?>

<div class="input-group">
    <?= $form->field($model, 'q1', ['options' => ['tag' => false], 'errorOptions' => ['tag' => false]])->textInput(['placeholder' => 'Search'])->label(false) ?>

    <div class="input-group-btn">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary']) ?>
    </div>

</div>
<?php ActiveForm::end(); ?>
<?php
$js = <<< JS
$("body").on("beforeSubmit", "form#filter-category", function (e) {
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