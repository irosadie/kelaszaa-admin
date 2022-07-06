<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Master Dana Bos';

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <p>
                <?= Html::a('<i class="fa fa-plus"></i> Master Dana Bos', ['create'], ['class' => 'btn btn-info m-1']) ?>
            </p>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action">
                        <?= $this->render('_search', ['model' => $searchModel]) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    function isInput($key, $label, $type = 'text', $value = '', $name, $cols = 8, $disabled = true, $currency = false)
                    {
                        return '<div class="form-group" data-key="' . $key . '">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                            </div>
                                            ' . Html::input($type, $name, $value, ['class' => 'form-control-md is-field' . ' col-' . $cols . ' ' . ($currency ? 'is-currency' : ''), 'placeholder' => $label, 'data-key' => $key, 'data-id' => $name . '-' . $key, 'disabled' => $disabled]) . '
                                        </div>
                                    </div>';
                    };
                    function isCombobox($key, $label, $value = '', $name, $cols = 8, $disabled = true, $month)
                    {
                        return '<div class="form-group" data-key="' . $key . '">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                            </div>
                                            ' . Html::dropDownList($name, $value, $month ?? [], ['class' => 'form-control-md is-field ' . 'col-' . $cols, 'placeholder' => $label, 'data-key' => $key, 'data-id' => $name . '-' . $key, 'disabled' => $disabled, 'prompt' => '--pilih bulan--']) . '
                                        </div>
                                    </div>';
                    };
                    ?>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'tableOptions' => ['class' => 'table table-striped'],
                            'afterRow' => function ($model, $key, $index, $grid) use ($month) {
                                $head_tabs = [];
                                $content_tabs = [];
                                foreach ($model->disbursementPlans as $iskey => $isvalue) :
                                    $head_tabs[] = '<li class="nav-item">
                                                        <a class="nav-link ' . ($iskey == 0 ? "active" : "") . '" id="profile-tab4" data-toggle="tab" href="#disbursement-' . ($model->id) . "-" . ($iskey + 1) . '" role="tab" aria-controls="disbursement" aria-selected="false">Pencairan ' . ($iskey + 1) . '</a>
                                                    </li>';

                                    $content_tabs[] = '<div class="tab-pane fade show ' . ($iskey == 0 ? "active" : "") . '" id="disbursement-' . ($model->id) . "-" . ($iskey + 1) . '" role="tabpanel" aria-labelledby="disbursement">
                                                            ' . isInput($key, 'Nama Pencairan', 'text', $isvalue->name, 'name[' . $isvalue->id . ']', '8', true) . isInput($key, 'Persentasi Pencairan', 'number', $isvalue->percentage_estimate, 'percentage_estimate[' . $isvalue->id . ']', '3', true) . isCombobox($key, 'Estimasi Bulan', $isvalue->month, 'month[' . $isvalue->id . ']', '8', true, $month) . isInput($key, 'Estimasi Pencairan', 'text', $isvalue->amount_estimate, 'amount_estimate[' . $isvalue->id . ']', '6', true) . '
                                                      </div>';
                                endforeach;

                                return '<tr class="row-collapse collapse-' . $key . '" style="display: none;">
                                        <td colspan="12" class="p-0">
                                            <div class="row" style="margin:0px; padding-bottom:12px; background-color:#fefefe;">
                                                <div class="card-header">
                                                    <h4 class="ml-n2">Detail Penerimaan Dana</h4>
                                                    <div class="card-header-action">
                                                        <button type="button" class="btn btn-sm btn-warning btn-edit" data-key="' . $key . '">ubah</button>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                                        ' . join("", $head_tabs) . '
                                                    </ul>
                                                </div>
                                                <div class="col-9">
                                                    <div class="tab-content no-padding" id="myTab2Content">
                                                        ' . join("", $content_tabs) . '
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';
                            },
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'contentOptions' => ['style' => 'width:10px;'],
                                    'header' => 'No.'
                                ],
                                [
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:32px;'],
                                    'value' => function ($model, $key) {
                                        return '<i data-key="' . $key . '" style="cursor: pointer; border-radius:999px" class="fas fa-arrow-down btn-collapse p-2 btn-warning"></i>';
                                    }
                                ],
                                [
                                    'attribute' => 'name',
                                    'label' => Yii::t('app', 'Nama Pengaturan'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return  $model->name ? "<strong>{$model->name}</strong>" : "";
                                    }
                                ],
                                [
                                    'attribute' => 'year',
                                    'label' => Yii::t('app', 'Banyak Pencarian'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return "<span class='tw-bg-red-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>{$model->disbursement_in_year} Kali</span>";
                                    }
                                ],
                                [
                                    'attribute' => 'schools',
                                    'label' => Yii::t('app', 'Sekolah'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:210px;'],
                                    'value' => function ($model) {
                                        $isReturn = "";
                                        if ($model->schools) :
                                            $school = json_decode($model->schools);
                                            foreach ($school as $key => $val) :
                                                $isReturn .= "<p class='tw-mb-1 tw-bg-blue-400 tw-whitespace-nowrap tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>{$val->text}</p>";
                                            endforeach;
                                        else :
                                            $isReturn = "<span class='tw-bg-blue-400 tw-px-3 tw-whitespace-nowrap tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>Semua Sekolah</span>";
                                        endif;
                                        return "<div>$isReturn</div>";
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => Yii::t('app', 'Status'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        return $model->status == 1 ? "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'Publish') . "</span>" : "<span class='tw-bg-blue-400 tw-text-xs tw-px-3 tw-py-1 tw-rounded-full tw-text-white'>" . Yii::t('app', 'Draf') . "</span>";
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width:150px;'],
                                    'header' => 'Action',
                                    'visibleButtons' => [
                                        'update' => false,
                                        'delete' => true,
                                        'view' => true,
                                    ],
                                    'template' => '{view}{delete}',
                                    'buttons' => array(
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-edit"></i> ', Url::to(['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-warning m-1']);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::button('<i class="fas fa-trash"></i>', ['class' => 'btn btn-sm btn-danger m-1', 'data-key' => Yii::$app->encryptor->encodeUrl($model->id)]);
                                        }
                                    )
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS

function init(){
    
    $(".is-currency").maskMoney({ thousands:'.', decimal:',', affixesStay: false, precision: 0});

    $('.btn-collapse').click(function(e){
        e.preventDefault();
        const key = $(this).data('key');
        let selector = $('.collapse-'+key)
        if($(selector).is(':visible')) {
            $('.collapse-' + key).hide('slow');
            $('i[data-key="'+key+'"]').removeClass('fa-arrow-up');
            $('i[data-key="'+key+'"]').addClass('fa-arrow-down');
        } else {
            $('.collapse-' + key).show('slow');
            $('i[data-key="'+key+'"]').removeClass('fa-arrow-down');
            $('i[data-key="'+key+'"]').addClass('fa-arrow-up');
        }
    });

    $('.btn-edit').click(function(){
        let key = $(this).data('key');
        if($(this).hasClass('btn-warning')){
            $('.is-field[data-key="'+key+'"]').removeAttr('disabled');
            $(this).html('simpan');
            $(this).removeClass("btn-warning");
            $(this).addClass("btn-primary");
        }
        else{
            let key = $(this).data('key');           
            let data = $('.is-field[data-key="'+key+'"]');
            
            $('.is-field[data-key="'+key+'"]').attr('disabled','disabled');
            $(this).html('ubah');
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-warning");
            
            let fd = new FormData();
            data.map(function(i, el){
                fd.append(el.name, el.value);
            })
            $.ajax({
                url: baseUrl + module+'/'+controller+'/update-disbursement-plan',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data){
                    if(data.status){
                        $.notify("Berhasil disimpan", "success");
                    }
                    else{
                        $.notify("Gagal disimpan", "error");
                    }
                }
            });
        }
    });
}

init();



$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>