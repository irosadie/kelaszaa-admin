<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\{
    Pjax
};
use yii\grid\GridView;

$this->title = Yii::t('app', 'Detail RKAT');
?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="row">
    <div class="col-12 col-lg-8 col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body pb-4">
                <span>
                    <h1 class="tw-text-lg"><?= $model->name ?? "-" ?></h1>
                    <p class="tw-text-sm"><?= $model->desc ?? "-" ?></p>
                </span>
                <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                    <div class="w-full">
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Sekolah') ?>:</p>
                        <p
                            class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>
                            <?= $model->school->nama ?? "-" ?></p>
                    </div>
                    <div class="tw-w-full">
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Tahun Anggaran') ?>:</p>
                        <?= $model->year ? "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . $model->year . "</span>" : "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'tidak disebutkan') . "</span>" ?>
                    </div>
                    <div class="tw-w-full">
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Master Juknis') ?>:</p>
                        <?= $model->juknis->name ?? "-"; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <tr>
                            <td><strong>Created at</strong></td>
                            <td>:</td>
                            <td><?= date("d/m/Y h:m:s", $model->created_at) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Created by</strong></td>
                            <td>:</td>
                            <td><?= $model->createdBy->full_name ?? "-" ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td><?= $model->status == 1 ? "<span class='badge badge-success'>Publish</span>" : "<span class='badge badge-primary'>Draft</span>" ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <?= Yii::$app->users->can(["operator"]) ? Html::button('<i class="fas fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1 float-right', 'data-pjax' => 0, 'style' => 'color:#fff', 'id' => 'delete', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]) : "";  ?>
                    <?= Yii::$app->users->can(["operator"]) ? Html::a('<i class="fas fa-edit"></i> ', Url::to(['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-warning m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]) : "";  ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', 'index', ['class' => 'btn btn-sm btn-info m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $status = 0; ?>
<?php if (Yii::$app->users->can(['operator']) || Yii::$app->users->can(['school_treasurer', 'person_responsible'], [$model->school_id])) : ?>
<?php $status = 1; ?>
<div class="row">
    <div class="col-12 tw-flex tw-gap-x-4 mb-3">
        <?= Html::button('<i class="fas fa-edit"></i> ' . Yii::t('app', 'mode buat'), ['class' => 'btn btn-primary', 'id' => 'create']) ?>
        <?= Html::button('<i class="fas fa-eye"></i> ' . Yii::t('app', 'mode tinjau'), ['class' => 'btn btn-light', 'id' => 'review']) ?>
    </div>
</div>
<?php endif; ?>
<?php if ((!Yii::$app->users->can(['treasurer']) && !Yii::$app->users->can(['headmaster'], [$model->school_id])) || Yii::$app->users->can(["operator"])) : ?>
<div id="create-content" class="row">
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Item Juknis') ?></h4>
            </div>
            <div class="card-body pb-4">
                <?= $this->render('_search_wb', ['model' => $itemJuknisSearch, 'name' => 'q2']) ?>
                <div class="table-responsive mt-4" style="max-height:680px;">
                    <?php Pjax::begin(['id' => 'pjax1']); ?>
                    <?= GridView::widget([
                            'dataProvider' => $itemJuknisProvider,
                            'tableOptions' => ['class' => 'table table-striped'],
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'summary' => false,
                            'showHeader' => false,
                            'columns' => [
                                [
                                    'attribute' => 'name',
                                    'label' => Yii::t('app', 'Nama Juknis'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        $ctg = $model->juknisItem->parent->value ?? "-";
                                        $isvalue = $model->juknisItem->value;
                                        return  "<p class='mt-2 mb-0'>{$isvalue}</p><span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>{$ctg}</span>";
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width:10px;'],
                                    'header' => 'Action',
                                    'visibleButtons' => [
                                        'update' => false,
                                        'delete' => false,
                                        'view' => true,
                                    ],
                                    'template' => Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["person_responsible", "school_treasurer"], [$model->school_id]) ? '{view}' : '',
                                    'buttons' => array(
                                        'view' => function ($url, $model, $key) {
                                            return Html::button('<i class="fas fa-angle-right"></i> ', ['class' => 'btn btn-sm btn-light m-1 float-right switch-right', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]);
                                        }
                                    )
                                ],
                            ],
                        ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-7">
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Item RKAT') ?></h4>
            </div>
            <div class="card-body pb-4">
                <?= $this->render('_search_wb', ['model' => $itemRkatSearch, 'name' => 'q1']) ?>
                <div class="table-responsive mt-4" style="max-height:680px;">
                    <?php Pjax::begin(['id' => 'pjax2']); ?>
                    <?= GridView::widget([
                            'dataProvider' => $itemRkatProvider,
                            'tableOptions' => ['class' => 'table table-striped'],
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'summary' => false,
                            'showHeader' => false,
                            'columns' => [
                                [
                                    'attribute' => 'name',
                                    'label' => Yii::t('app', 'Item RKAT'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        $ctg = $model->juknisRelation->juknisItem->parent->value ?? "";
                                        $value = $model->juknisRelation->juknisItem->value ?? "-";
                                        return  "<p class='mt-2 mb-0'>{$value}</p><span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>{$ctg}</span>";
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width:10px;'],
                                    'header' => 'Action',
                                    'visibleButtons' => [
                                        'update' => false,
                                        'delete' => true,
                                        'view' => false,
                                    ],
                                    'template' => Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["person_responsible", "school_treasurer"], [$model->school_id]) ? '{delete}' : '',
                                    'buttons' => array(
                                        'delete' => function ($url, $model, $key) {
                                            return $model->validation_level == 'treasurer' ? "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs float-right'>disetujui</span>" : Html::button('<i class="fas fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1 float-right item-delete', 'style' => 'color:#fff', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]);
                                        }
                                    )
                                ],
                            ],
                        ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div id="review-content" class="row" style="display: <?= $status ? 'none' : 'block' ?>">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Item RKAT') ?></h4>
                <div class="card-header-action">
                    <?= $this->render('_search_iv', ['model' => $itemRkatSearch, 'name' => 'q1']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive tw-relative">
                    <?php Pjax::begin(['id' => 'pjax3']); ?>
                    <?= Html::beginForm('update-amounts', 'POST', ['id' => 'update-amounts']) ?>
                    <?= $itemRkatProvider->getTotalCount() >= 1 && (Yii::$app->users->can(["person_responsible"], [$model->school_id]) || Yii::$app->users->can(["operator"])) ? Html::button('edit', ['id' => 'edit', 'class' => 'btn btn-warning btn-sm tw-absolute tw-right-0 tw-px-4', 'data-pjax' => 0]) : '' ?>
                    <?= GridView::widget([
                        'dataProvider' => $itemRkatProvider,
                        'tableOptions' => ['class' => 'table table-striped'],
                        'summaryOptions' => ['class' => 'badge badge-light m-2'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'contentOptions' => ['style' => 'width:10px;'],
                                'header' => 'No.'
                            ],
                            [
                                'label' => Yii::t('app', 'Item RKAT'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:300px;'],
                                'value' => function ($model) {
                                    $ctg = $model->juknisRelation->juknisItem->parent->value ?? "";
                                    $value = $model->juknisRelation->juknisItem->value ?? "-";
                                    return  "<p class='mt-2 mb-0'>{$value}</p><span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>{$ctg}</span>";
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Anggaran Diajukan'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:120px;'],
                                'value' => function ($model) {
                                    return $model->amount_estimate && $model->validation_level != 'person_responsible' ? "<p>IDR " . number_format($model->amount_estimate) . "</p>" : Html::input('text', 'amount_estimate[' . $model->id . ']', $model->amount_estimate ? number_format($model->amount_estimate, 0, '.', '.') : 0, ['class' => 'form-control amount', 'disabled' => 'disabled']);
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Status '),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:120px;'],
                                'value' => function ($model) {
                                    return $model->validations && $model->validation_level == 'treasurer' ?  "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'disetujui') . "</span>" : "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'pengajuan') . "</span>";
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Level Peninjauan'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:120px;'],
                                'value' => function ($model) {
                                    $level_str = "";
                                    switch ($model->validation_level):
                                        case "school_treasurer":
                                            $level_str = Yii::t('app', "b'hara sekolah");
                                            break;
                                        case "headmaster":
                                            $level_str = Yii::t('app', "kepsek");
                                            break;
                                        case "treasurer":
                                            $level_str = Yii::t('app', "b'hara yayasan");
                                            break;
                                        default:
                                            $level_str = Yii::t('app', "pj danabos");;
                                            break;
                                    endswitch;
                                    return "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-whitespace-nowrap tw-rounded-full tw-text-white tw-text-xs'>" . $level_str . "</span>";
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'contentOptions' => ['style' => 'width:96px;'],
                                'header' => 'Action',
                                'visibleButtons' => [
                                    'update' => false,
                                    'delete' => false,
                                    'view' => true,
                                ],
                                'template' => Yii::$app->users->can([]) ? '{view}' : '',
                                'buttons' => array(
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('<i class="fas fa-file"></i> ' . Yii::t('app', 'detail'), Url::to(['item-detail', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-primary m-1 tw-whitespace-nowrap', 'style' => 'color:#fff', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]);
                                    }
                                )
                            ],
                        ],
                    ]); ?>
                    <?= Html::endForm() ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsVar("is_code", Yii::$app->encryptor->encodeUrl($model->id));

$js = <<< JS
function processData(type, code){
    let url;
    switch(type){
        case "delete":
            url= baseUrl+module+'/'+controller+'/delete'
            break;
        case "item-delete":
            url= baseUrl+module+'/'+controller+'/item-delete'
            break;
    }
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        preConfirm: function (data) {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: url,
                    data: {
                        code: code,
                        _csrf: _csrf
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    success:function(result){
                        resolve(result.status);
                    },
                });

            })
        },
    }).then(function (data) {
        if(data==1){
            swal(
                'Delete Success',
                'Data berhasil di hapus :)',
                'success'
            ).then(function () {
                if(type=="item-delete"){
                    $.pjax.reload({container: '#pjax1', timeout: false}).done(function () {
                        $.pjax.reload({container: '#pjax2', timeout: false}).done(function () {
                            $.pjax.reload({container: '#pjax3', timeout: false});
                        });
                    });
                }
                else{
                    $.pjax({url:'index', container:'#p0', timeout: false});
                }
            });
        }
        else if(data==-1){
            swal(
                'Oups Galat!!!',
                'Sepertinya ada yang salah, coba ulangi',
                'error'
            ).then(function () {
                window.location.reload();
            });
        }
        else{
            swal(
                'Ups!!!',
                'Anda Tidak memiliki hak untuk menghapus lagi',
                'error'
            ).then(function () {
                window.location.reload();
            });
        }
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            swal(
            'Cancelled',
            'Your imaginary file is safe :)',
            'error'
            )
        }
    });
}
function switchRights(code){
    $.ajax({
        url: baseUrl+module+'/'+controller+'/switch-item',
        data: {
            rkat_id: is_code, //table rkat
            code: code, //table juknis_relation
            _csrf: _csrf
        },
        type: 'POST',
        dataType: 'JSON',
        success:function(result){
            $.pjax.reload({container: '#pjax1', timeout: false}).done(function () {
                $.pjax.reload({container: '#pjax2', timeout: false}).done(function () {
                    $.pjax.reload({container: '#pjax3', timeout: false}).done(function () {
                        if(result.status==1){
                            $.notify("Berhasil", "success");
                        }
                        else if(result.statu==-1){
                            $.notify("Oups, gagal!", "warn");
                        }
                        else{
                            $.notify("Hak akses gagal", "error");
                        }
                    })
                });
            });
        },
    });
}
function init(){
    $('#create').click(function(){
        $(this).removeClass('btn-light').addClass('btn-primary');
        $('#review').removeClass('btn-primary').addClass('btn-light');
        $('#review-content').hide();
        $('#create-content').show('slow');
    });
    $('#review').click(function(){
        $(this).removeClass('btn-light').addClass('btn-primary');
        $('#create').removeClass('btn-primary').addClass('btn-light');
        $('#create-content').hide();
        $('#review-content').show('slow');
    });

    let check = 0;
    $('#edit').click(function(e){
        e.preventDefault();
        if($(this).hasClass('btn-primary')){
            $(this).removeClass('btn-primary');
            $(this).removeClass('btn-success');
            $(this).html('edit');
            if(check==1){
                $.ajax({
                    url: $('form#update-amounts').attr('action'),
                    data: $('form#update-amounts').serialize(),
                    type: $('form#update-amounts').attr('method'),
                    dataType: 'JSON',
                    success:function(result){
                        let {status, msg} = result;
                        $.pjax.reload({container: '#pjax3', timeout: false}).done(function(){
                            if(status==1){
                                $.notify(msg, "success");
                            }
                            else if(status==-1){
                                $.notify(msg, "warn");
                                
                            }
                            else{
                                $.notify("Hak akses gagal", "error");
                            }
                            $('input.amount').attr('disabled', 'disabled');
                        })
                    },
                });
                check = 0;
                e.stopImmediatePropagation();
            }
        }
        else{
            $(this).removeClass('btn-success');
            $(this).addClass('btn-primary');
            $(this).html('save');
            $('input.amount').removeAttr('disabled');
            check=1;
        }
        return;
    })
    $("input.amount").maskMoney({ thousands:'.', decimal:',', affixesStay: false, precision: 0});
    $('input.amount').keyup(function(){
        let value = $(this).val();
        $(this).maskMoney('mask', value, { thousands:'.', decimal:',', affixesStay: false, precision: 0});
    })
    //notify
    $('#delete').click(function(){
        processData("delete", $(this).attr('data'))
    });
    $('#add-item').click(function(e){
        e.preventDefault();
        $('#modalTitle').html('Tambah Item Juknis')
        let url = $(this).attr('href');
        $.get(url+'?code='+is_code, function(data) {
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    });
    $('.switch-right').click(function(e){
        e.preventDefault();
        switchRights($(this).attr('data'));
        e.stopImmediatePropagation();
        return false;
    });
    $('.item-delete').click(function(e){
        e.preventDefault();
        processData("item-delete", $(this).attr('data'))
        return false
    })
};
// call function
init()
$('.lazy').Lazy()
$(document).on('pjax:complete', function() {
  init()
  $('.lazy').Lazy()
});
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>