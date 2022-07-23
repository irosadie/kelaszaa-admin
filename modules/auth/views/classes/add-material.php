<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\ActiveForm;
use app\utils\template\Template;
?>

<?php $form = ActiveForm::begin([
    'id' => 'create-material',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'options' => ['data-pjax' => 1],
    'validationUrl' => Url::toRoute(['validate-material']),
]); ?>
<div class="row">
    <div class="col-12 col-lg-12 col-md-12">
        <div class="form">
            <?= $form->field($model, 'media')->fileInput([
                'class' => 'filepond2',
                'data-allow-reorder' => true,
                'required' => true,
                'data-max-file-size' => '3MB',
                'data-max-files' => '1',
                'value' => null,
            ])->label(Yii::t('app', 'Media*')) ?>
            <?= $form->field($model, 'thumbnail')->fileInput([
                'class' => 'filepond',
                'data-allow-reorder' => true,
                'required' => false,
                'data-max-file-size' => '3MB',
                'data-max-files' => '1',
                'value' => null,
            ])->label(Yii::t('app', 'Thumbnail')) ?>
            <?= $form->field($model, 'title', Template::template('fas fa-user'))->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => Yii::t('app', 'Materi 1')])->label(Yii::t('app', 'Judul*')) ?>
            <?= $form->field($model, 'desc')->textArea(['maxlength' => true, 'placeholder' => Yii::t('app', ''), 'class' => 'form-control'])->label(Yii::t('app', 'Deskripsi*')) ?>
            <?= $form->field($model, 'topic_id')->hiddenInput(['value' => $parent->id])->label(false) ?>
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
    let hasClick = 0;
    $("body").on("beforeSubmit", "form#create-material", function (e) {
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
}
init()
JS;
$this->registerJs($js);
?>