<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\utils\template\Template;
?>

<?php $form = ActiveForm::begin([
    'enableClientValidation'=>true,
    'enableAjaxValidation'=>true,
    'options'=>['data-pjax'=>1],
    'validationUrl' => Url::toRoute(['validate']),
    ]); ?>
<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body col-6 m-auto">
                <div class="form">

                    <?= $form->field($model, 'username', Template::template('fas fa-at'))->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email', Template::template('fas fa-envelope'))->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'full_name', Template::template('fas fa-user'))->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'phone', Template::template('fas fa-phone'))->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'address')->textArea(['maxlength' => true, 'placeholder' =>'', 'class' =>'form-control']) ?>

                    <?= $form->field($model, 'biography')->textArea(['maxlength' => true, 'placeholder' =>'', 'class' =>'form-control']) ?>

                </div>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-sm btn-primary m-1 float-right']) ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> Back', Yii::$app->request->referrer, ['class' => 'btn btn-sm btn-info m-1 float-right']);  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$homeUrl=Yii::$app->homeUrl;
$csrf=Yii::$app->request->getCsrfToken();
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
            dataType : 'json',
            success: function (response){
                let {status, from, id} = response;
                if(from=='create' || from=='update' && status){
                    $.pjax({url:'me', container:'#p0', timeout: false});
                    return;
                }
                $.pjax.reload({container: '#p0', async: false});
            },
            error  : function () {
                window.location.reload();
            }
        });
        hasClick++;
        return false;
    });
}
init();
JS;
$this->registerJs($js);
?>