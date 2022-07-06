<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

/* @var $model \app\models\helpers\ResetPasswordForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Request password reset';

?>


<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

            <div class="card card-primary">
                <div class="card-header"><h4>Forgot Password</h4></div>

                <div class="card-body">
                    <p>Please fill out your email. A link to reset password will be sent there.</p>


                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'email@example.com']) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
            <div class="simple-footer">
                Copyright &copy; Stisla <?= date('Y') ?>
            </div>
        </div>
    </div>
</div>
