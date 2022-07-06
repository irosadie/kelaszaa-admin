<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\ActiveForm;
use app\utils\template\Template;
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-item-juknis',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'options' => ['data-pjax' => 1],
    'validationUrl' => Url::toRoute($model->isNewRecord ? ['validate-item-juknis'] : ['validate-item-juknis', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]),
]); ?>
<div class="row">
    <div class="col-12">
        <div class="form" id="multiple-form">
            <?= $form->field($model, 'value')->textArea(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control'])->label(Yii::t('app', 'Item')) ?>
            <?= $form->field($model, 'parent_id')->dropDownList($parents ?? [], ['class' => 'form-control get-parent-juknis select2', 'multiple' => false, 'value' => isset($parents) ? array_keys($parents) : [], 'prompt' => '--choose one--'])->label(Yii::t('app', 'Kategori')); ?>
            <?= Html::checkbox('include', true, ['label' => 'Masukkan ke Juknis Sekarang']) ?>
            <?= Html::input('hidden', 'code', $code) ?>
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
    $('.get-parent-juknis').select2({
        ajax: {
            url: 'get-parent-juknis',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            }
        }
    });
    let hasClick = 0;
    $("body").on("beforeSubmit", "form#form-item-juknis", function (e) {
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
                    $.pjax.reload({container: '#pjax1', timeout: false, async: true}).done(function () {
                        $.pjax.reload({container: '#pjax2', timeout: false, async: true});
                    });
                }
                return;
            },
            error  : function (e) {
                // alert(JSON.stringify(e))
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