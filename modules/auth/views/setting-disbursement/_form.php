<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\ActiveForm;
use app\utils\template\Template;
?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'options' => ['data-pjax' => 1],
    'validationUrl' => Url::toRoute($model->isNewRecord ? ['validate'] : ['validate', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]),
]); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body col-7 m-auto">
                <?= $form->field($model, 'name', Template::template('fas fa-hashtag'))->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Nama Pengaturan')])->label(Yii::t('app', 'Nama Pengaturan')) ?>
                <?= $form->field($model, 'desc')->textArea(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control'])->label(Yii::t('app', 'Deskripsi')) ?>
                <?= $form->field($model, 'schools')->dropDownList($schools ?? [], ['class' => 'form-control get-schools select2', 'multiple' => true, 'value' => isset($schools) ? array_keys($schools) : [], 'prompt' => '--choose one--'])->label(Yii::t('app', 'Sekolah (Multi)')); ?>
                <?= $form->field($model, 'disbursement_in_year')->dropDownList([1 => '1 Kali', 2 => '2 Kali', 3 => '3 Kali', 4 => '4 Kali', 5 => '5 Kali', 6 => '6 kali'], ['class' => 'form-control select2', 'prompt' => '--choose one--', 'disabled' => $model->isNewRecord ? false : true])->label(Yii::t('app', 'Banyaknya Pencairan')); ?>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn-submit btn btn-sm btn-primary m-1 float-right']) ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> Back', $model->isNewRecord ? 'index' : Url::to(['view', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-info m-1 float-right']);  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJsVar('slug', "");

$js = <<< JS


function init(){
    let hasClick = 0;
    $("body").on("beforeSubmit", "form", function (e) {
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
                    $.pjax({url:'index', container:'#p0', timeout: false});
                    return;
                }
                $.pjax.reload({container: '#p0', timeout: false});
            },
            error  : function (e) {
                window.location.reload();
                // alert(JSON.stringify(e));
            }
        });
        hasClick++;
        e.stopImmediatePropagation();
        return false;
    });

    FilePond.registerPlugin(FilePondPluginFileValidateType);
    const inputElement = document.querySelector('.filepond');

    const pond = FilePond.create(inputElement);
    pond.setOptions({
        acceptedFileTypes: ['image/jpeg', 'image/gif', 'image/png'],
        server:'handle-file',
    });

    pond.on('processfile', (error, file) => {
        if (error) {
            $(".btn-submit").prop("disabled", true);
            pond2.setOptions({'disabled':true})
            return;
        }
        $(".btn-submit").prop("disabled", false);
        pond2.setOptions({'disabled':false})
    });

    pond.on('addfile', (error, file) => {
        $(".btn-submit").prop("disabled", true);
        pond2.setOptions({'disabled':true})
    });

    const inputElement2 = document.querySelector('.filepond2');
    const pond2 = FilePond.create(inputElement2);
    pond2.setOptions({
        acceptedFileTypes: ['image/jpeg', 'image/gif', 'image/png'],
        server:'handle-file',
    });

    pond2.on('processfile', (error, file) => {
        if (error) {
            $(".btn-submit").prop("disabled", true);
            pond.setOptions({'disabled':true})
            return;
        }
        $(".btn-submit").prop("disabled", false);
        pond.setOptions({'disabled':false})

    });

    pond2.on('addfile', (error, file) => {
        $(".btn-submit").prop("disabled", true);
        pond.setOptions({'disabled':true})
    });

    $('.get-year-of-graduates').select2({
        ajax: {
            url: 'get-year-of-graduates',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            }
        }
    });

    $('.get-schools').select2({
        ajax: {
            url: 'get-schools',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            }
        }
    });

    $('.get-juknis').select2({
        ajax: {
            url: 'get-juknis',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            }
        }
    });

    $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
    });

    function convertStringToUrl(str){
        let url = str.split(" ").join("-").replace(/[^a-z^A-Z^0-9^/-]+/g, "").toLowerCase();
        return url;
    }

    let urlHasChanged = false;
    $('input#announcements-slug').keyup((e)=>{
        urlHasChanged = true;
        if(e.target.value==convertStringToUrl($('input#announcements-title').val())){
            urlHasChanged = false;
        }
        $('input#announcements-slug').val(convertStringToUrl(e.target.value));
    })

    $('input#announcements-title').keyup((e)=>{
        if(!urlHasChanged){
            let url = convertStringToUrl(e.target.value);
            slug = url;
            $('input#announcements-slug').val(url);   
        }
    })

    $('.summernote').summernote({
        height: 200
    });
}
init()
JS;
$this->registerJs($js);
?>