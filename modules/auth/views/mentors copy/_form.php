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
    <div class="col-12 col-lg-8 col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'title', Template::template('fas fa-hashtag'))->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Nama RKAT')])->label(Yii::t('app', 'Nama RKAT')) ?>
                <?= $form->field($model, 'desc')->textArea(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control', 'id' => 'tiny'])->label(Yii::t('app', 'Desc')) ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="form">
                    <?= $form->field($model, 'mentor_id')
                        ->dropDownList($year ?? [], ['class' => 'form-control select2', 'value' => !$model->isNewRecord ? $model->year : NULL, 'prompt' => '--choose one--'])->label(Yii::t('app', 'Tahun Anggaran')); ?>
                    <?= $form->field($model, 'status')
                        ->dropDownList([2 => Yii::t('app', 'Draf'), 1 => Yii::t('app', 'Aktif')], ['class' => 'form-control select2'])->label(Yii::t('app', 'Status'));
                    ?>
                </div>
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
    tinymce.init({
        selector: 'textarea#tiny',
        // plugins: 'tinydrive',
        height: 600,
        // tinydrive_token_provider: 'http://localhost/project/side/kelaszaa.id/public/site/jwt',
        // tinydrive_upload_path: '/img',
        // // tinydrive_google_drive_key: 'AIzaSyDgbH7zsxMHzYP24bP8ByF138uBCEl3Q2c',
        // // tinydrive_google_drive_client_id: '265815473526-9bvo3v22jub0in9gjujfmr4ilkeqb7vi.apps.googleusercontent.com',
        // toolbar: 'insertfile image link | code'
        plugins: "tinydrive links code file-manager",
        Flmngr: {
            urlFileManager: 'https://fm.flmngr.com/fileManager',
            urlFiles: 'https://fm.flmngr.com/files/'
        },
        toolbar: "Upload, Flmngr, ImgPen"
    });
    
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
                if((from=='create') && status){
                    $.pjax({url:'index', container:'#p0', timeout: false});
                    return;
                }
                if((from=='update') && status){
                    $.pjax({url:'view?code='+id, container:'#p0', timeout: false});
                    return;
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

    $('.select2').select2();

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
}
init()
JS;
$this->registerJs($js);
?>