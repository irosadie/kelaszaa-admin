<?php

use yii\helpers\{
    Html,
    Url,
    HtmlPurifier
};
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'List Pencairan Dana';

?>
<?php Pjax::begin(); ?>
<?= $this->render('@app/views/site/_message') ?>
<div class="index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4> <?= $this->title ?></h4>
                    <div class="card-header-action tw-flex">
                        <?= isset(Yii::$app->request->get('Disbursement')['date_begin']) ? Html::button('<i class="fas fa-undo"></i> reset', ['data-href' => 'index', 'id' => 'reset', 'class' => 'mr-4 btn btn-warning tw- tw-whitespace-nowrap']) : '' ?>
                        <?= Html::button('<i class="fas fa-filter"></i> filter', ['data-href' => Yii::$app->homeurl . 'auth/purchase/filter', 'id' => 'filter', 'class' => 'mr-4 btn btn-primary tw- tw-whitespace-nowrap']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'summaryOptions' => ['class' => 'badge badge-light m-2'],
                            'tableOptions' => ['class' => 'table table-striped'],
                            'afterRow' => function ($model, $key, $index, $grid) {
                                $date = Yii::t('app', 'Tanggal');
                                $item = Yii::t('app', 'Item');
                                $total = Yii::t('app', 'Jumlah');
                                $amount = Yii::t('app', 'Harga Total');
                                $receipt = Yii::t('app', 'Kwitansi');
                                $action = Yii::t('app', 'Aksi');
                                $reports = "<tr><td colspan='7' style='text-align:center;'>Tidak/ Belum ada Laporan</td></tr>";
                                if ($model->purchaseReports) :
                                    $reports = "";
                                    foreach ($model->purchaseReports as $iskey => $isvalue) :
                                        $isdelete = (time() - $isvalue->created_at <= 36001) ? '<a class="dropdown-item delete" data-key="' . $key . '" data-id="' . Yii::$app->encryptor->encodeUrl($isvalue->id) . '" href="#">hapus</a>' : '';
                                        $dropdown = '<div class="btn-group mb-2">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    ' . Yii::t('app', 'aksi') . '
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);">
                                                        <a href="view?code=' . Yii::$app->encryptor->encodeUrl($isvalue->id) . '" class="dropdown-item" href="#">detail</a>
                                                        ' . $isdelete . '
                                                    </div>
                                                </div>';
                                        $print = $isvalue->proof_of_payments ? '<a data-pjax="0" href="' . $isvalue->proof_of_payments . '" target="_blank"><button class="btn btn-sm btn-info"><i class="fas fa-print"></i></button></a>' : 'tidak tersedia';
                                        $reports .= '<tr><td>#</td><td>' . date('d/m/Y', strtotime($isvalue->date)) . '</td><td>' . $isvalue->item_name . '</td><td>' . $isvalue->item_total . '</td><td> IDR ' . number_format($isvalue->amount_total, 0, '.', '.') . '</td><td>' . $print . '</td><td>' . $dropdown . '</td></tr>';
                                    endforeach;
                                    $remaining = $model->amount_approved - $isvalue->totalPurchase;
                                    $reports .= "<tr><td>&nbsp;</td><td colspan='3'><strong>Total</strong></td><td colspan='3'> <strong>IDR " . number_format($isvalue->totalPurchase, 0, '.', '.') . "</strong></td></tr>";
                                    $reports .= "<tr><td>&nbsp;</td><td colspan='3'><strong>Sisa Dana</strong></td><td colspan='3'> <strong style='" . ($remaining ? "color:red;" : "") . "'>IDR " . number_format(($remaining), 0, '.', '.') . "</strong></td></tr>";
                                endif;
                                return '<tr class="row-collapse collapse-' . $key . '" style="display: none;">
                                        <td colspan="12" class="p-0">
                                            <div class="card-header">
                                                <h4>Laporan Pembelian</h4>
                                            </div>
                                            <div class="row" style="margin:0px; padding-bottom:12px; background-color:#fefefe;">
                                                <table class="table table-sm"><tr><td style="width:32px;">#</td><th style="width:128px;">' . $date . '</th><th style="width:320px;">' . $item . '</th><th style="width:96px;">' . $total . '</th><th>' . $amount . '</th><th>' . $receipt . '</th><th>' . $action . '</th></tr>' . $reports . '</table>
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
                                    'attribute' => 'created_at',
                                    'label' => Yii::t('app', 'Tanggal'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:96px;'],
                                    'value' => function ($model) {
                                        return  date('d/m/Y', $model->created_at);
                                    }
                                ],
                                [
                                    'attribute' => 'period_id',
                                    'label' => Yii::t('app', 'Periode'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:240px;'],
                                    'value' => function ($model) {
                                        return $model->disbursementPlan->name;
                                    }
                                ],
                                [
                                    'attribute' => 'schools',
                                    'label' => 'School',
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:180px;'],
                                    'value' => function ($model) {
                                        return "<p class='tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-whitespace-nowrap tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>{$model->rkatItem->rkat->school->nama}</p>";
                                    }
                                ],
                                [
                                    'attribute' => 'amount',
                                    'label' => 'Jumlah',
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:210px;'],
                                    'value' => function ($model) {
                                        return "<p class='tw-whitespace-nowrap tw-mb-1 tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-w-fit'>IDR " . number_format($model->amount_approved, 0, '.', '.') . "</p>";
                                    }
                                ],
                                [
                                    'label' => Yii::t('app', 'Laporan'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        return "<span class='tw-bg-yellow-400 tw-text-xs tw-px-3 tw-py-1 tw-rounded-full tw-text-white'>" . count($model->purchaseReports) . "</span>";
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => Yii::t('app', 'Status'),
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:50px;'],
                                    'value' => function ($model) {
                                        return $model->validations ? "<span class='tw-bg-green-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'disetujui') . "</span>" : "<span class='tw-bg-yellow-400 tw-text-xs tw-px-3 tw-py-1 tw-rounded-full tw-text-white'>" . Yii::t('app', 'pengajuan') . "</span>";
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width:200px;'],
                                    'header' => 'Action',
                                    'visibleButtons' => [
                                        'update' => false,
                                        'delete' => false,
                                        'view' => false,
                                    ],
                                    'template' => '{add}',
                                    'buttons' => array(
                                        'add' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-plus"></i> ', Url::to(['create', 'code' => Yii::$app->encryptor->encodeUrl($model->id)]), ['class' => 'btn btn-sm btn-primary m-1 add-report', 'data-key' => $key]);
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
function processData(type, code, key=NULL){
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
                $.pjax.reload({container: '#p0', timeout: false}).then(function(){
                    $('.collapse-' + key).show('slow');
                    $('i[data-key="' + key + '"]').removeClass('fa-arrow-down');
                    $('i[data-key="' + key + '"]').addClass('fa-arrow-up');
                });
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
    $('a.add-report').click(function(e){
        e.preventDefault();
        let key = $(this).data('key');
        $('#modalTitle').html('Laporan Penggunaan Dana')
        let url = $(this).attr('href');
        $.get(url, function(data) {
            data = "<div>"+data+"<span id='iskey' data-key="+key+">&nbsp;</span></div>";
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    });
    
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

    $('#filter').click(function(e){
        e.preventDefault();
        var str = window.location.search.replace("?", "");
        $('#modalTitle').html('Filter')
        let url = $(this).data('href');
        $.get(url+(str?("?"+str):""), function(data) {
            $('#modal').modal('show').find('#modalContent').html(data)
        });
    })

    $('#reset').click(function(e){
        $.pjax({url:'index', container:'#p0', timeout: false});
    })

    $('a.delete').click(function(e){
        e.preventDefault();
        processData("delete", $(this).data('id'), $(this).data('key'))
    });
};
// call function
init()
$('.lazy').Lazy()
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>