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
            <?= $form->field($model, 'value[]', Template::template2('fas fa-edit'))->textInput(['maxlength' => true, 'placeholder' => 'Kategori Juknis'])->label(false) ?>
        </div>
        <div class="form-group">
            <?= Html::a('<i class="fas fa-plus"></i> Add Field', "", ['class' => 'btn btn-sm btn-warning add-field m-1 float-right']);  ?>
            <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn-submit btn btn-sm btn-primary m-1 float-right']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php

$js = <<< JS
function init(){
    
    $('a.add-field').click(function(e){
        e.preventDefault();
        let field = $(':first-child', '#multiple-form').html();
        field = field.replace('style="cursor: not-allowed"', 'style="cursor: pointer"')
        $('div.form').append(field)
    });
    
    $(document).on("click", "div.delete-field", function (){
        if($(this).css("cursor")!="not-allowed"){
            $(this).closest("div.form-group").remove();
        }
    })
    
    let hasClick = 0;
    $("body").on("beforeSubmit", "form#form-categories", function (e) {
        var form = $(this);
        if (form.find(".has-error").length || hasClick > 0) 
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
                if((from=='create') && status){
                    $('#modal').modal('hide')
                }
                $.pjax.reload({container: '#p0', timeout: false});
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