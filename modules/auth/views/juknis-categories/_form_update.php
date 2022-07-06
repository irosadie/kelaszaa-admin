<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\ActiveForm;
use app\utils\template\Template;
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-categories',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'options' => ['data-pjax' => 1],
    'validationUrl' => Url::toRoute($model->isNewRecord ? ['validate', 'validate' => 0] : ['validate', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]),
]); ?>
<div class="row">
    <div class="col-12">
        <div class="form" id="multiple-form">
            <?= $form->field($model, 'value', Template::template('fas fa-edit'))->textInput(['maxlength' => true, 'placeholder' => 'Kategori Juknis'])->label(false) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn-submit btn btn-sm btn-primary m-1 float-right']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php

$js = <<< JS
function init(){
    
    let hasClick = 0;
    $("body").on("beforeSubmit", "form#form-categories", function (e) {
        var form = $(this);
        if (form.find(".has-error").length || hasClick > 0 || !$('#juknisitem-value').val()) 
        {
            return false;
        }
        $.ajax({
            url : form.attr("action"),
            type : form.attr("method"),
            data : form.serialize(),
            dataType : 'JSON',
            success: function (response){
                let {status, from, id} = response;
                if((from=='update') && status){
                    $('#modal').modal('hide')
                }
                $.pjax.reload({container: '#p0', timeout: false});
                return;
            },
            error  : function (e) {
                window.location.reload();
            }
        });
        hasClick++;
        e.stopImmediatePropagation();
        return false;
    });
}
init()
JS;
$this->registerJs($js);
?>