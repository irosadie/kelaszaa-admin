<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Pengaturan Umum');

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <?= Html::beginForm('update', 'POST', ['id' => 'app-config']) ?>
    <div class="row">
        <div class="col-12 mb-4">
            <?= Html::submitButton('<i class="fas fa-save"></i> save', ['class' => 'btn btn-primary btn-sm float-right']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><label class="control-label"
                                for="app-name_long"><?= Yii::t('app', 'Nama Aplikasi (Penuh)') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_name_long', $app_name_long ?? "", ['class' => 'form-control', 'placeholder' => 'DANABOS APP']) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><label class="control-label"
                                for="app-name_short"><?= Yii::t('app', 'Nama Aplikasi (Pendek)') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_name_short', $app_name_short ?? "", ['class' => 'form-control', 'placeholder' => 'DB']) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><label class="control-label"
                                for="app-domain"><?= Yii::t('app', 'Domain') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_domain', $app_domain ?? "", ['class' => 'form-control', 'placeholder' => 'https://www.domain.com']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4> <?= Yii::t('app', 'Pengaturan Aplikasi') ?></h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><label class="control-label"
                                for="config-year"><?= Yii::t('app', 'Tahun Penggunaan') ?></label></label>
                        <div class="input-group">
                            <?= Html::dropDownList('app_config_year', $app_config_year ?? "", $year, ['class' => 'form-control select2', 'placeholder' => date('Y')]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><label class="control-label"
                                for="config-date_rkat_open"><?= Yii::t('app', 'Tanggal RKAT Buka') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_config_date_rkat_open', $app_config_date_rkat_open ?? "", ['class' => 'form-control datepicker', 'placeholder' => date('Y-m-d')]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><label class="control-label"
                                for="config-date_rkat_close"><?= Yii::t('app', 'Tanggal RKAT Tutup') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_config_date_rkat_close', $app_config_date_rkat_close ?? "", ['class' => 'form-control datepicker', 'placeholder' => date('Y-m-d')]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><label class="control-label"
                                for="config-date_disbursement_open"><?= Yii::t('app', 'Tanggal Pelaporan Buka') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_config_date_disbursement_open', $app_config_date_disbursement_open ?? "", ['class' => 'form-control datepicker', 'placeholder' => date('Y-m-d')]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><label class="control-label"
                                for="config-date_disbursement_open"><?= Yii::t('app', 'Tanggal Pelaporan Tutup') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_config_date_disbursement_close', $app_config_date_disbursement_close ?? "", ['class' => 'form-control datepicker', 'placeholder' => date('Y-m-d')]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><label class="control-label"
                                for="config-report_can_delete"><?= Yii::t('app', 'Lama Pelaporan Bisa Dihapus (second)') ?></label></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </div>
                            <?= Html::input('text', 'app_config_report_can_delete', $app_config_report_can_delete ?? "", ['class' => 'form-control', 'placeholder' => '36000']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= Html::endForm() ?>
</div>
<?php
$js = <<< JS
function init(){
    $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
    });
    $('form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            success: function(response){
                let {status, msg} = response;
                if(status == 1){
                    $.notify(msg, 'success');
                }else{
                    $.notify(msg, 'error');
                }
            },
            error: function(e){
                window.location.reload();
            }
        });
        e.stopImmediatePropagation();
        return;
    })
}
init();
$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>