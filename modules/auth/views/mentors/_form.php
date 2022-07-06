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
                <?= $form->field($model, 'full_name', Template::template('fas fa-user'))->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => Yii::t('app', 'Samsul Bahri, M.T')])->label(Yii::t('app', 'Nama Lengkap')) ?>
                <?= $form->field($model, 'gender', Template::radio())
                    ->radioList([1 => Yii::t('app', 'Laki-laki'), 2 => Yii::t('app', 'Perempuan')], ['value' => $model->isNewRecord ? 1 : $model->gender])->label(Yii::t('app', 'Jenis Kelamin')); ?>
                <?= $form->field($model, 'born_in', Template::template('fas fa-map-marker-alt'))->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => Yii::t('app', 'Pekanbaru')])->label(Yii::t('app', 'Tempat Lahir')) ?>
                <?= $form->field($model, 'born_at', Template::template('fas fa-calendar'))->textInput(['maxlength' => true, 'class' => 'form-control datepicker', 'placeholder' => Yii::t('app', date('Y-m-d'))])->label(Yii::t('app', 'Tanggal Lahir')) ?>
                <?= $form->field($model, 'address')->textArea(['maxlength' => true, 'placeholder' => Yii::t('app', 'Jl. Sukamaju'), 'class' => 'form-control'])->label(Yii::t('app', 'Alamat')) ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="form">
                    <?= $form->field($model, 'phone', Template::template('fas fa-phone'))->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => Yii::t('app', '085265279959')])->label(Yii::t('app', 'Nomor Ponsel')) ?>
                    <?= $form->field($model, 'email', Template::template('fas fa-at'))->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'zurnalis@kelaszaa.id')])->label(Yii::t('app', 'Username/ Email')) ?>
                    <?php if ($model->isNewRecord) : ?>
                    <?= $form->field($model, 'password_hash', Template::template('fas fa-eye'))->passwordInput(['maxlength' => true, 'placeholder' => Yii::t('app', '********')])->label(Yii::t('app', 'Password')) ?>
                    <?php endif; ?>
                    <?= $form->field($model, 'avatar', Template::image())->fileInput([
                        'class' => 'filepond',
                        'data-allow-reorder' => true,
                        'data-max-file-size' => '3MB',
                        'data-max-files' => '1'
                    ])->label(Yii::t('app', 'Upload Avatar')) ?>
                    <?= $form->field($model, 'status')
                        ->dropDownList([10 => Yii::t('app', 'Aktif'), 9 => Yii::t('app', 'Butuh Konfirmasi'), 0 => 'Tidak Aktif', -1 => 'Lainnya'], ['class' => 'form-control select2'])->label(Yii::t('app', 'Status'));
                    ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'simpan'), ['class' => 'btn-submit btn btn-sm btn-primary m-1 float-right']) ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ' . Yii::t('app', 'kembali'), $model->isNewRecord ? 'index' : Url::to(['view', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-info m-1 float-right']);  ?>
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
    
    let hasClick = 0;
    $("body").on("beforeSubmit", "form", function (e) {
        var form = $(this);
        // if (form.find(".has-error").length || hasClick > 0) 
        // {
        //     return false;
        // }
        $.ajax({
            url : form.attr("action"),
            type : form.attr("method"),
            data : form.serialize(),
            dataType : 'JSON',
            success: function (response){
                //masih ada alert
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
                //masih ada alert
                alert(JSON.stringify(e));
                // window.location.reload();
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

    $('.select2').select2();

    $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
        showDropdowns: true,
    });
}
init()
JS;
$this->registerJs($js);
?>