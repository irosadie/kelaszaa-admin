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
        <div class="form row" id="multiple-form">
            <div class="col-12">
                <?= $form->field($model, 'item_name')->textArea(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control'])->label(Yii::t('app', 'Nama Barang')) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'date', Template::template('fas fa-edit'))->textInput(['maxlength' => true, 'class' => 'form-control datepicker'])->label(Yii::t('app', 'Tanggal')) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'item_total', Template::template('fas fa-edit'))->textInput(['type' => 'number', 'maxlength' => true, 'placeholder' => '10'])->label(Yii::t('app', 'Total Barang')) ?>
            </div>
            <div class="col-6 mt-2">
                <?= $form->field($model, 'unit_str')->dropDownList([], ['class' => 'form-control get-units select2', 'multiple' => false, 'prompt' => '--choose one--'])->label(Yii::t('app', 'Satuan')); ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'amount_total', Template::template('fas fa-edit'))->textInput(['maxlength' => true, 'placeholder' => '100.000'])->label(Yii::t('app', 'Harga Total')) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'desc')->textArea(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control'])->label(Yii::t('app', 'Keterangan')) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'photos', Template::image())->fileInput([
                    'class' => 'filepond',
                    'data-allow-reorder' => true,
                    'data-max-file-size' => '3MB',
                    'required' => false,
                    'data-max-files' => '1'
                ])->label(Yii::t('app', 'Foto Barang')) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'proof_of_payments', Template::image())->fileInput([
                    'class' => 'filepond',
                    'data-allow-reorder' => true,
                    'data-max-file-size' => '3MB',
                    'required' => false,
                    'data-max-files' => '1'
                ])->label(Yii::t('app', 'Kwitansi')) ?>
            </div>

            <div class="col-12 tw-py-2 tw-bg-[#cacaca] tw-mb-2">Informasi Pembelian</div>

            <div class="col-6 mt-2">
                <?= $form->field($model, 'store_name')->dropDownList([], ['class' => 'form-control get-store-names select2', 'multiple' => false, 'prompt' => '--choose one--'])->label(Yii::t('app', 'Toko/ Suplier')); ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'store_phone', Template::template('fas fa-edit'))->textInput(['maxlength' => true, 'placeholder' => 'No Hp Toko'])->label(Yii::t('app', 'No Hp Toko')) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'store_address')->textArea(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control'])->label(Yii::t('app', 'Alamat Toko')) ?>
            </div>

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
    $("#purchasereport-amount_total").maskMoney({ thousands:'.', decimal:',', affixesStay: false, precision: 0});
    $("#purchasereport-amount_total").keyup(function(){
        let value = $(this).val();
        $("input#disbursement-amount_request").maskMoney('mask', value, { thousands:'.', decimal:',', affixesStay: false, precision: 0});
    })
    
    $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
    });

    $('#purchasereport-store_name').change(function(){
        $.ajax({
            url : baseUrl+module+'/'+controller+'/get-store-info',
            type : 'GET',
            data : {
                store_name : $(this).val()
            },
            dataType : 'JSON',
            success: function (response){
                let {phone, address} = response.results;
                if(phone){
                    $('#purchasereport-store_phone').val(phone);
                }
                if(address){
                    $('#purchasereport-store_address').val(address);
                }
                $('#purchasereport-store_address').focus();
                return;
            },
            error  : function (e) {
                console.log('error get phone and addrss')
            }
        });
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

    $('.get-store-names').select2({
        dropdownParent: $("#modal"),
        ajax: {
            url: 'get-store-names',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            }
        }
    });

    $('.get-units').select2({
        dropdownParent: $("#modal"),
        ajax: {
            url: 'get-units',
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