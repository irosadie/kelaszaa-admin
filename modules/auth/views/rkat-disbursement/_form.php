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
    'validationUrl' => Url::toRoute($model->isNewRecord ? ['validate'] : ['validate', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]),
]); ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-light alert-has-icon alert-dismissible show fade">
            <div class="alert-icon"><i class="fas fa-bell"></i></div>
            <div class="alert-body">
                <div class="alert-title">Maksimum Penarikan!</div>
                <p>Maksimum penarikan dana yang bisa dilakukan adalah sebesar: <strong>IDR
                        <?= number_format($max_disbursement, 0, '.', '.') ?></strong></p>
            </div>
        </div>
        <div class="form row" id="multiple-form">
            <div class="col-8">
                <?= $form->field($model, 'amount_request', Template::template('fas fa-edit'))->textInput(['maxlength' => true, 'placeholder' => '3.000.000'])->label(Yii::t('app', 'Jumlah Dana')) ?>
            </div>
            <div class="col-4">
                <?= $form->field($model, 'percentage', Template::template('fas fa-edit'))->textInput(['type' => 'number', 'maxlength' => true, 'placeholder' => '10'])->label(Yii::t('app', 'Persentase')) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'desc')->textArea(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control'])->label(Yii::t('app', 'Deskripsikan Penggunaan Dana')) ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn-submit btn btn-sm btn-primary m-1 float-right']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJsVar('max_disbursement', $max_disbursement);

$js = <<< JS
function init(){
    $("input#disbursement-amount_request").maskMoney({ thousands:'.', decimal:',', affixesStay: false, precision: 0});

    $("input#disbursement-percentage").keyup(function(){
        let isValue = $(this).val();
        if(isValue>=100){
            isValue = 100;
            $(this).val(isValue);
        }
        let isAmount = (isValue/100) * max_disbursement
        $("input#disbursement-amount_request").maskMoney('mask', isAmount, { thousands:'.', decimal:',', affixesStay: false, precision: 0});
    })

    $("input#disbursement-amount_request").keyup(function(){
        let isValue = $(this).val().replace(/\./g, '');
        let isAmount = 0;
        let isPercentage = 0;
        if(isValue>=max_disbursement){
            isAmount = max_disbursement
            isPercentage = 100;
        }
        else{
            isAmount = isValue;
            isPercentage = (isValue/max_disbursement) * 100;
        }
        $("input#disbursement-percentage").val(Math.round(isPercentage));
        $("input#disbursement-amount_request").maskMoney('mask', isAmount, { thousands:'.', decimal:',', affixesStay: false, precision: 0});
    })

    let hasClick = 0;
    $("body").on("beforeSubmit", "form#form-categories", function (e) {
        var form = $(this);
        if (form.find(".has-error").length || hasClick > 0 || max_disbursement <=0 || $("input#disbursement-amount_request").val() <=0 ) 
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
                let key = $('span#iskey').data('key');
                if((from=='create') && status){
                    $('#modal').modal('hide')
                    $.pjax.reload({container: '#p0', timeout: false}).then(function(){
                        $('.collapse-' + key).show('slow');
                        $('i[data-key="'+key+'"]').removeClass('fa-arrow-down');
                        $('i[data-key="'+key+'"]').addClass('fa-arrow-up');
                    });
                }
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

    FilePond.registerPlugin(FilePondPluginFileValidateType);
    
    const inputElements = document.querySelectorAll('input[type="file"]');

    // loop over input elements
    Array.from(inputElements).forEach(inputElement => {
        const pond = FilePond.create( inputElement );
        pond.setOptions({
            acceptedFileTypes: ['image/jpeg', 'image/gif', 'image/png'],
            server: 'handle-file',
        });
        pond.on('processfile', (error, file) => {
            if (error) {
                $(".btn-submit").prop("disabled", true);
                return;
            }
            $(".btn-submit").prop("disabled", false);
        });

        pond.on('addfile', (error, file) => {
            $(".btn-submit").prop("disabled", true);
        });
    })

    $('.get-store-name').select2({
        dropdownParent: $("#modal"),
        ajax: {
            url: 'get-store-name',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            }
        }
    });
    
}
init()
JS;
$this->registerJs($js);
?>