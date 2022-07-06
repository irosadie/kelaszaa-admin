<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\template\Template;
use yii\widgets\Pjax;
use yii\helpers\Url;
$this->title = "Change Photo";
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>

            <div class="card-body col-6 m-auto">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'photo', Template::image())->fileInput([
                        'class'=>'filepond',
                        'data-allow-reorder'=>true,
                        'data-max-file-size'=>'3MB',
                        'required'=>$model->photo?false:true,
                        'data-max-files'=>'1'
                    ])->label('Upload Image') ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-file"></i> Add New' : '<i class="fas fa-save"></i> Save', ['class' => 'btn btn-sm btn-primary m-1 float-right btn-submit']) ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> Back', 'me', ['class' => 'btn btn-sm btn-info m-1 float-right']);  ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>
<?php
$homeUrl=Yii::$app->homeUrl;
$csrf=Yii::$app->request->getCsrfToken();
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
                    $.pjax({url:'me', container:'#p0', timeout: false});
                    return;
                }
            },
            error  : function () {
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