<?php

use yii\helpers\{
    Html,
    Url
};
use yii\widgets\{
    Pjax
};

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
                <div class="tw-block">
                    <h1 title="<?= Yii::t('app', 'nama RKAT') ?>" class="tw-text-lg"><?= $model->name ?? "-" ?></h1>
                </div>
                <div class="tw-text-sm"><?= $model->desc ?? "-" ?></div>
                <div class="tw-grid tw-space-y-3 tw-mt-3">
                    <div class="tw-flex">
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Sekolah') ?>:</p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= $model->school->nama ?></span>
                        </div>
                        <div class="tw-w-full">
                            <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Tahun Anggaran') ?>:</p>
                            <span
                                class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'><?= $model->year ?></span>
                        </div>
                    </div>
                    <span>
                        <p class="tw-text-base tw-font-bold tw-mb-1"><?= Yii::t('app', 'Master Juknis') ?>:</p>
                        <p class="tw-text-sm tw-font-bold"><?= $model->juknis->name ?></p>
                    </span>
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
                    <?= Html::a('<i class="fas fa-undo-alt"></i> ' . Yii::t('app', 'kembali'), 'index', ['class' => 'btn btn-sm btn-info m-1 float-right', 'style' => 'color:#fff', 'data-pjax' => 1]);  ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><?= Yii::t('app', 'Item RKAT') ?></h4>
            </div>
            <div class="card-body pb-4">
                <div class="alert alert-light alert-has-icon alert-dismissible show fade">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    <div class="alert-icon"><i class="fas fa-bell"></i></div>
                    <div class="alert-body">
                        <div class="alert-title"><?= Yii::t('app', 'Periode Penarikan Dana') ?></div>
                        <?php if ($setting) : ?>
                        <p><?= $setting->disbursementMaster->name ?> - <?= $setting->name; ?></p>
                        <?php else : ?>
                        <p><?= Yii::t('app', 'Saat ini tidak ada periode penarikan dana yang aktif!') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <?= \yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summaryOptions' => ['class' => 'badge badge-light m-2'],
                        'tableOptions' => ['class' => 'table table-striped'],
                        'afterRow' => function ($model, $key, $index, $grid) {
                            $date = Yii::t('app', 'Tanggal');
                            $period = Yii::t('app', 'Periode');
                            $desc = Yii::t('app', 'Deskripsi');
                            $amount_request = Yii::t('app', 'Jumlah');
                            $status = Yii::t('app', 'Status');
                            $action = Yii::t('app', 'Aksi');
                            $reports = "<tr><td colspan='6' style='text-align:center;'>Tidak/ Belum ada Laporan</td></tr>";
                            if ($model->disbursements) :
                                $reports = "";
                                foreach ($model->disbursements as $iskey => $isvalue) :
                                    $grant = false;
                                    if (Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["school_treasurer", "headmaster", "person_responsible"], [$model->rkat->school_id])) :
                                        $grant = true;
                                    endif;
                                    $dropdown = $grant ? '<div class="btn-group mb-2">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    ' . Yii::t('app', 'aksi') . '
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);">
                                                        <a href="detail?code=' . Yii::$app->encryptor->encodeUrl($isvalue->id) . '" class="dropdown-item" href="#">detail</a>
                                                        <a class="dropdown-item item-delete" data-key="' . $key . '" data-id="' . Yii::$app->encryptor->encodeUrl($isvalue->id) . '" href="#">hapus</a>
                                                    </div>
                                                </div>' : '-';
                                    $validate = $isvalue->validation_level == 'treasurer' ? "<span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'disetujui') . "</span>" : "<span class='tw-bg-yellow-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . Yii::t('app', 'pengajuan') . "</span>";
                                    $reports .= '<tr><td>#</td><td>' . date('d/m/Y', $isvalue->created_at) . '</td><td>' . $isvalue->disbursementPlan->name . '</td><td>' . $isvalue->desc . '</td><td> <span class="tw-whitespace-nowrap">IDR ' . number_format($isvalue->amount_request, 0, '.', '.') . '</span></td><td>' . $validate . '</td><td>' . $dropdown . '</td></tr>';
                                endforeach;
                                $reports .= "<tr><td>&nbsp;</td><td colspan='3'><strong>T O T A L</strong></td><td colspan='3'> <strong class='tw-whitespace-nowrap'>IDR " . number_format(($model->amount_estimate - $model->remainingFunds), 0, '.', '.') . "</strong></td></tr>";
                            endif;
                            return '<tr class="row-collapse collapse-' . $key . '" style="display: none;">
                                        <td colspan="12" class="p-0">
                                            <div class="card-header">
                                                <h4>List Pencairan Dana</h4>
                                            </div>
                                            <div class="row" style="margin:0px; padding-bottom:12px; background-color:#fefefe;">
                                                <table class="table table-sm"><tr><td style="width:32px;">#</td><th style="width:128px;">' . $date . '</th><th style="width:180px;">' . $period . '</th><th style="width:320px;">' . $desc . '</th><th>' . $amount_request . '</th><th>' . $status . '</th><th>' . $action . '</th></tr>' . $reports . '</table>
                                            </div>
                                        </td>
                                    </tr>';
                        },
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'contentOptions' => ['style' => 'width:32px;'],
                                'header' => 'No.',
                            ],
                            [
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:32px;'],
                                'value' => function ($model, $key) {
                                    return '<i data-key="' . $key . '" style="cursor: pointer; border-radius:999px" class="fas fa-arrow-down btn-collapse p-2 btn-warning"></i>';
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Nama Juknis'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:300px;'],
                                'value' => function ($model) {
                                    $ctg = $model->juknisRelation->juknisItem->parent->value ?? "-";
                                    return  "<p class='mt-2 mb-0'>{$model->juknisRelation->juknisItem->value}</p><span class='tw-bg-blue-400 tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs tw-whitespace-nowrap'>{$ctg}</span>";
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Disetujui'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:300px;'],
                                'value' => function ($model) {
                                    return "<span class='tw-whitespace-nowrap'>IDR " . number_format($model->amount_estimate, 0, '.', '.') . "</span>";
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Sisa Dana'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:300px;'],
                                'value' => function ($model) {
                                    return "<span class='tw-whitespace-nowrap'>IDR " . number_format($model->remainingFunds, 0, '.', '.') . "</span>";
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Total Pengajuan'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:50px;'],
                                'value' => function ($model) {
                                    return "<span class='tw-bg-yellow-400 tw-whitespace-nowrap tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>" . (count($model->disbursements)) . "</span>";
                                }
                            ],
                            [
                                'label' => Yii::t('app', 'Status'),
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:50px;'],
                                'value' => function ($model) {
                                    return "<span class='tw-bg-green-400 tw-whitespace-nowrap tw-px-3 tw-py-1 tw-rounded-full tw-text-white tw-text-xs'>disetujui</span>";
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
                                'template' => Yii::$app->users->can(["operator"]) || Yii::$app->users->can(["headmaster", "school_treasurer", "person_responsible"], [$model->school_id]) ? '{add}' : '-',
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
<?php
$js = <<< JS
function processData(type, code, key=NULL){
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
                if(type=="delete"){
                    $.pjax({url:'index', container:'#p0', timeout: false});
                }
                if(type=="item-delete"){
                    $.pjax.reload({container: '#p0', timeout: false}).then(function(){
                        $('.collapse-' + key).show('slow');
                        $('i[data-key="'+key+'"]').removeClass('fa-arrow-down');
                        $('i[data-key="'+key+'"]').addClass('fa-arrow-up');
                    });
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
function init(){
    $('a.add-report').click(function(e){
        e.preventDefault();
        let key = $(this).data('key');
        $('#modalTitle').html('Tambah Pengajuan Dana')
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

    $('#delete').click(function(){
        processData("delete", $(this).attr('data'))
    });

    $('.item-delete').click(function(){
        processData("item-delete", $(this).data('id'), $(this).data('key'));
    });
};
// call function
init()
$('.lazy').Lazy()
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>