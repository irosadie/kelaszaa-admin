<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => ['view', 'code' => Yii::$app->request->get('code')],
    'method' => 'get',
    'options' => [
        'data-pjax' => true
    ],
]); ?>

<div class="input-group">
    <?= $form->field($model, $name, ['options' => ['tag' => false], 'errorOptions' => ['tag' => false]])->textInput(['placeholder' => 'Search'])->label(false) ?>
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