<?php

use yii\helpers\{
    Html,
    Url
};

use yii\widgets\ActiveForm;
use app\utils\template\Template;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Ubah Foto');
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/message/alert') ?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>

            <div class="card-body col-6 m-auto">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'avatar', Template::image())->fileInput([
                    'class' => 'filepond',
                    'data-allow-reorder' => true,
                    'data-max-file-size' => '3MB',
                    'required' => $model->avatar ? false : true,
                    'data-max-files' => '1'
                ])->label(Yii::t('app', 'unggah foto')) ?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'simpan'), ['class' => 'btn btn-sm btn-primary m-1 float-right btn-submit']) ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ' . Yii::t('app', 'kembali'), ['view', 'code' => Yii::$app->encryptor->encodeUrl($model->id)], ['class' => 'btn btn-sm btn-info m-1 float-right']);  ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>

<?php
$modelEncrypt = Yii::$app->encryptor->encodeUrl($model->id);
$this->registerJsVar('redirectUrl', "view?code={$modelEncrypt}");

$js = <<< JS
$('document').ready(()=>{
    $(".btn-submit").prop("disabled", true);
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
            dataType : 'json', 
            success: function (response){
                let {status, from, id} = response;
                if(from=='create' || from=='update' && status){
                    $.pjax({url:redirectUrl, container:'#p0', timeout: false});
                    return;
                }
            },
            error  : function (e) {
                window.location.reload();
            }
        });
        hasClick++;
        return false;
    });
    
    FilePond.registerPlugin(FilePondPluginFileValidateType);
    const inputElement = document.querySelector('input[type="file"]');
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
});
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>