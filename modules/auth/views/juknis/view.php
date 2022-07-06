<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\{
    ActiveForm,
    DetailView,
    Pjax
};
use yii\grid\GridView;


$this->title = 'Detail Juknis';
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
                    <p class="tw-text-sm"><?= $model->desc ?></p>
                </span>
                <div class="tw-grid tw-grid-cols-2 tw-gap-y-4">
                    <div class="w-full">
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Sekolah') ?>:</p>
                        <?php
                        if ($model->schools) :
                            $school = json_decode($model->schools);
                            echo "<div class='tw-flex tw-flex-wrap tw-gap-1'>";
                            foreach ($school as $key => $val) :
                                echo "<p class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>{$val->text}</p>";
                            endforeach;
                            echo "</div>";
                        else :
                            echo "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>Semua Sekolah</span>";
                        endif;
                        ?>
                    </div>
                    <div class="tw-w-full">
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Tahun Anggaran') ?>:</p>
                        <?= $model->year ? "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . $model->year . "</span>" : "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'tidak disebutkan') . "</span>" ?>
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
                            <td><strong><?= Yii::t('app', 'Tanggal dibuat') ?></strong></td>
                            <td>:</td>
                            <td><?= date("d/m/Y h:m:s", $model->created_at) ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Dibuat oleh') ?></strong></td>
                            <td>:</td>
                            <td><?= $model->createdBy->full_name ?? "-" ?></td>
                        </tr>
                        <tr>
                            <td><strong><?= Yii::t('app', 'Status') ?></strong></td>
                            <td>:</td>
                            <td><?= $model->status == 1 ? "<span class='badge badge-success'>publish</span>" : "<span class='badge badge-primary'>draft</span>" ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <?= Html::button('<i class="fas fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1 float-right', 'data-pjax' => 0, 'style' => 'color:#fff', 'id' => 'delete', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]);  ?>
                    <?= Html::a('<i class="fas fa-edit"></i> ', Url::to(['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-warning m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ', 'index', ['class' => 'btn btn-sm btn-info m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Semua Item Juknis') ?></h4>
                <div class="card-header-action">
                    <?= Html::button('<i class="fas fa-plus"></i> ' . Yii::t('app', 'tambah'), ['type' => 'button', 'href' => 'add-juknis-item', 'class' => 'btn btn-primary', 'title' => Yii::t('app', 'Tambah Juknis'), 'id' => 'add-item']) ?>
                </div>
            </div>
            <div class="card-body pb-4">
                <?= $this->render('_search_wb', ['model' => $allItemJuknisSearch, 'name' => 'q1']) ?>
                <div class="table-responsive mt-4" style="max-height:680px;">
                    <?php Pjax::begin(['id' => 'pjax1']); ?>
                    <?= GridView::widget([
                        'dataProvider' => $allItemJuknisProvider,
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
                                    $ctg = $model->parent->value ?? "-";
                                    return  "<p class='mt-2 mb-0'>{$model->value}</p><span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>{$ctg}</span>";
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
                                'template' => '{view}',
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
                <h4><?= Yii::t('app', 'Item Juknis') ?></h4>
            </div>
            <div class="card-body pb-4">
                <?= $this->render('_search_wb', ['model' => $itemJuknisSearch, 'name' => 'q2']) ?>
                <div class="table-responsive mt-4" style="max-height:680px;">
                    <?php Pjax::begin(['id' => 'pjax2']); ?>
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
                                    $value = $model->juknisItem->value ?? "-";
                                    return  "<p class='mt-2 mb-0'>{$value}</p><span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>{$ctg}</span>";
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
                                'template' => '{view}',
                                'buttons' => array(
                                    'view' => function ($url, $model, $key) {
                                        return Html::button('<i class="fas fa-trash"></i> ', ['class' => 'btn btn-sm btn-danger m-1 float-right item-delete', 'style' => 'color:#fff', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]);
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
                        $.pjax.reload({container: '#pjax2', timeout: false});
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
            juknis_id: is_code,
            code: code,
            _csrf: _csrf
        },
        type: 'POST',
        dataType: 'JSON',
        success:function(result){
            $.pjax.reload({container: '#pjax1', timeout: false, async: true}).done(function () {
                $.pjax.reload({container: '#pjax2', timeout: false, async: true}).done(function () {
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
        },
    });
}
function init(){
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