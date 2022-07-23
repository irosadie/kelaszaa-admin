<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\ActiveForm;
use app\utils\template\Template;
?>

<?php $form = ActiveForm::begin([
    'id' => 'create-member',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'options' => ['data-pjax' => 1],
    'validationUrl' => Url::toRoute(['validate-member']),
]); ?>
<div class="row">
    <div class="col-12 col-lg-12 col-md-12">
        <div class="form">
            <?= $form->field($model, 'member_id')
                ->dropDownList([], ['class' => 'form-control select2', 'id' => 'get-members'])->label(Yii::t('app', 'Status'));
            ?>
            <?= $form->field($model, 'class_id')->hiddenInput(['value' => $parent->id])->label(false) ?>
            <div>
                <?= Html::checkbox('is_sure', false, ['id' => 'is-sure']) ?>
                <label for="is-sure"><?= Yii::t('app', 'Saya Yakin') ?></label>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'simpan'), ['class' => 'btn-submit btn btn-sm btn-primary m-1 float-right']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$js = <<< JS
function init(){
    $('input#is-sure').click(function(){
        if($(this).is(':checked') && $('#get-members').val()){
            $('.btn-submit').removeAttr('disabled');
            return
        }
        $('.btn-submit').attr('disabled', 'disabled');
    })
    $('#get-members').change(function() {
        if($('#is-sure').is(':checked') && $(this).val()){
            $('.btn-submit').removeAttr('disabled');
            return
        }
        $('.btn-submit').attr('disabled', 'disabled');
    })
    $('#get-members').select2({
        dropdownParent: '#modal',
        ajax: {
            url: 'get-members',
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
    $("body").on("beforeSubmit", "form#create-member", function (e) {
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
                $('#modal').modal('hide')
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