<?php

use yii\helpers\{
    Html, Url
};
use yii\widgets\ActiveForm;
use app\utils\template\Template;
?>

<?php $form = ActiveForm::begin(['id' =>'is-yog']); ?>
<div class="row">
    <div class="col-12">
        <div class="form">
            <div class="">
                <?= Html::dropDownList('yog', null, $yog, ['class' => 'form-control select2', 'prompt' =>'--Pilih Satu--']) ?>
                <?= Html::input('hidden', 'selection', $selection); ?>
                <div class="mt-4">
                    <?= Html::input('checkbox', 'sure', 1, ['id'=>'is-sure', 'disabled'=>'disabled']); ?>
                    <label for="is-sure">Saya yakin</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn-submit btn btn-sm btn-primary m-1 float-right', 'disabled'=>'disabled']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>

<?php
$js = <<< JS

function init(){
    let hasClick = 0;
    $("body").on("beforeSubmit", "form#is-yog", function (e) {
        e.preventDefault();
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
                    if((from=='create' || from=='update') && status){
                        $.pjax.reload({container: '#p0', timeout: false});
                        $('#modal').modal('hide');
                        return;
                    }
                    $.pjax.reload({container: '#p0', timeout: false});
                },
                error  : function (e) {
                    console.log(e)
                    // window.location.reload();
                }
        });
        hasClick++;
        e.stopImmediatePropagation();
        return false;
    });

    $('.select2').select2();

    $('select[name="yog"]').change(function(){
        if($(this).val()){
            $('#is-sure').removeAttr('disabled');
        }
        else{
            $('#is-sure').attr('disabled', 'disabled');
            $('.btn-submit').prop('disabled', true);
            $('#is-sure').prop('checked', false);
        }
    })
    
    $('#is-sure').change(function () {
        if($(this).is(':checked')){
           $('.btn-submit').prop('disabled', false);
        }
        else{
           $('.btn-submit').prop('disabled', true);
        }
    })
}
init()
JS;
$this->registerJs($js);
?>