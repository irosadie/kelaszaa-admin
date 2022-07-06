<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->title = "Change Password";
?>
<?php Pjax::begin() ?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body col-6 m-auto">
                <?php $form = ActiveForm::begin([
                    'enableClientValidation'=>true,
                    'enableAjaxValidation'=>true,
                    'options'=>['data-pjax'=>1],
                    'validationUrl' => Url::toRoute(['validate', 'scenario'=>'change-password']),
                ]); ?>

                <?= $form->field($model, 'old_password')->passwordInput(['placeholder' => 'Current Password'])->label('Current Password') ?>

                <?= $form->field($model, 'new_password')->passwordInput(['placeholder' => 'New Password'])->label('New Password') ?>

                <?= $form->field($model, 'repeat_password')->passwordInput(['placeholder' => 'Repeat Password'])->label('Repeat Password') ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-file"></i> Add New' : '<i class="fas fa-save"></i> Save', ['class' => 'btn btn-sm btn-primary m-1 float-right']) ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> Back', 'me', ['class' => 'btn btn-sm btn-info m-1 float-right', 'style'=>'color:#fff', 'data-pjax'=>1]);  ?>
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
                $.pjax.reload({container: '#p0', async: false});
            },
            error  : function (e) {
                window.location.reload();
            }
        });
        hasClick++;
        return false;
    });
});
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>