<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';

?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'fieldConfig' => [
        'template' => "<div class='d-block'>{label}</div>\n{input}\n{error}",
        'errorOptions' => ['class' => 'invalid-feedback'],
    ],
]); ?>

<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group text-right">
    <a href="<?= Url::to('site/logout', true) ?>" class="float-left mt-3">
        Forgot Password?
    </a>
    <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
        Login
    </button>
</div>

<?php ActiveForm::end(); ?>

