<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Kategori Juknis');

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <p>
                <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'Item Juknis'), ['create'], ['class' => 'btn btn-info m-1 add-item']) ?>
            </p>
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action">
                        <?= $this->render('_search', ['model' => $searchModel ?? []]) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider ?? [],
                            // 'filterModel' => $searchModel,
                            'tableOptions' => ['class' => 'table table-striped'],
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'contentOptions' => ['style' => 'width:10px;'],
                                    'header' => 'No.'
                                ],
                                [
                                    'attribute' => 'value',
                                    'label' => Yii::t('app', 'Juknis'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return "<p>$model->value</p>";
                                    }
                                ],
                                [
                                    'attribute' => 'parent_id',
                                    'label' => Yii::t('app', 'Kategori'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:300px;'],
                                    'value' => function ($model) {
                                        return "<p class='badge badge-primary'>#{$model->parent->value}</p>";
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
                                            return Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'ubah'), Url::to(['update', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-info m-1 edit']);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::button('<i class="fas fa-trash"></i> ' . Yii::t('app', 'hapus'), ['class' => 'btn btn-sm btn-danger m-1 delete', 'data-pjax' => 0, 'style' => 'color:#fff', 'data' => Yii::$app->encryptor->encodeUrl($model->id), 'data-pjax' => 1]);
                                        }
                                    )
                                ],
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
function processData(type, code){
    let url;
    switch(type){
        case "delete":
            url= baseUrl+module+'/'+controller+'/delete'
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
                $.pjax.reload({container: '#p0', timeout: false});
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

function init(){
    $('a.add-item').click(function(e){
        e.preventDefault();
        $('#modalTitle').html('Tambah Item Juknis')
        let url = $(this).attr('href');
        $.get(url, function(data) {
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    });
    

    $('a.edit').click(function(e){
        e.preventDefault();
        $('#modalTitle').html('Ubah Item Juknis')
        let url = $(this).attr('href');
        $.get(url, function(data) {
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    });
    
    $('.delete').click(function(){
        processData("delete", $(this).attr('data'))
    });
};

init()

$('.lazy').Lazy()

$(document).on('pjax:popstate', function(){
    $.pjax.reload({container: '#p0', timeout: false});
});

JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>