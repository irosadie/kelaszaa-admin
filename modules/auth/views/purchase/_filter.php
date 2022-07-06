<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\template\Template;
use yii\widgets\{
    Pjax
};
?>
<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'id' => 'filter-masters',
    'method' => 'get',
    'options' => [
        'data-pjax' => 1
    ],
]); ?>

<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'date_begin', Template::template('fas fa-edit'))->textInput(['value' => $date_begin ?? date('Y-01-01'), 'maxlength' => true, 'placeholder' => date('Y-m-d'), 'class' => 'form-control datepicker disbursement-date_begin'])->label(Yii::t('app', 'Tanggal Awal')) ?>
    </div>
    <div class="col-6 tw-relative">
        <div class="tw-absolute tw-w-full tw-flex tw-bottom-0 tw-right-0 ">
            <a href="" id="reset-date" class="col-12 tw-flex tw-justify-end">reset</a>
        </div>
        <?= $form->field($model, 'date_end', Template::template('fas fa-edit'))->textInput(['value' => $date_end ?? date('Y-m-d'), 'maxlength' => true, 'placeholder' => date('Y-m-d'), 'class' => 'form-control datepicker disbursement-date_end'])->label(Yii::t('app', 'Tanggal Akhir')) ?>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'school_id')->dropDownList($schools, ['class' => 'form-control get-schools', 'value' => $school_id ?? NULL, 'prompt' => '--pilih--'])->label(Yii::t('app', 'Sekolah')); ?>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'period_id')->dropDownList($periods, ['class' => 'form-control get-periods', 'value' => $period_id ?? NULL, 'prompt' => '--pilih--'])->label(Yii::t('app', 'Periode')); ?>
    </div>
    <div class="col-3">
        <?= $form->field($model, 'approve_status', Template::template('fas fa-edit'))->dropDownList([1 => 'disetujui', 2 => 'pengajuan'], ['class' => 'form-control', 'value' => $approve_status ?? NULL, 'prompt' => '----'])->label(Yii::t('app', 'Status')); ?>
    </div>
    <div class="col-4">
        <?= $form->field($model, 'operator', Template::template('fas fa-edit'))->dropDownList(['=' => 'sama dengan', '>' => 'lebih besar', '<' => 'lebih kecil', '>=' => 'kecil/ sama dengan', '<=' => 'besar/ sama dengan'], ['class' => 'form-control', 'value' => $operator ?? NULL])->label(Yii::t('app', 'Operator   ')); ?>
    </div>
    <div class="col-5">
        <?= $form->field($model, 'amount', Template::template('fas fa-edit'))->textInput(['type' => 'text', 'value' => $amount ?? NULL, 'maxlength' => true, 'placeholder' => '1.000.000'])->label(Yii::t('app', 'Jumlah')) ?>
    </div>
    <?= $form->field($model, 'query')->hiddenInput(['value' => $query ?? ""])->label(false) ?>
    <div class="form-group col-12">
        <?= Html::submitButton('<i class="fas fa-filter"></i> ' . Yii::t('app', 'filter'), ['data-pjax' => 1, 'class' => 'btn btn-submit btn btn-sm btn-primary m-1 float-right']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJsVar('begin', date('Y-01-01'));
$this->registerJsVar('now', date('Y-m-d'));

$js = <<< JS
$('#reset-date').click(function(e){
    e.preventDefault();
    $('.disbursement-date_begin').val(begin);
    $('.disbursement-date_end').val(now);
})
$("input#disbursement-amount").maskMoney({ thousands:'.', decimal:',', affixesStay: false, precision: 0});
$('#disbursement-amount').keyup(function(){
    let value = $(this).val();
    $(this).maskMoney('mask', value, { thousands:'.', decimal:',', affixesStay: false, precision: 0});
})
$('.get-schools').select2({
    dropdownParent: '#modal',
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
    $('.get-periods').select2({
    dropdownParent: '#modal',
    ajax: {
        url: 'get-periods',
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
$("body").on("beforeSubmit", "form#filter-masters", function (e) {
    var form = $(this);
    if (form.find(".has-error").length) 
    {
        return false;
    }
    $('.close').click();
    return true;
});
JS;
$this->registerJs($js);
Pjax::end();
?>