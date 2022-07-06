<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\StislaAsset;
use app\utils\breadcrumb\Breadcrumb as BC;
use yii\bootstrap4\{
    Modal,
    Html
};
use yii\helpers\Url;

StislaAsset::register($this);
$router = $this->context->action->uniqueId;
$breadcrumb = BC::generateBreadcrumbs($router, "breadcrumb-item");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<!-- <body class="d-flex flex-column h-100"> -->

<body>
    <?php $this->beginBody() ?>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar" style="z-index:unset">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin' || Yii::$app->user->identity->role == 'super_user') : ?>
                    <div class="form-group col-6">
                        <select class="form-control select2 get-main-schools">
                            <?php if (Yii::$app->session->get('school_id')) : ?>
                            <option selected value="<?= Yii::$app->session->get('school_id') ?>">
                                <?= Yii::$app->session->get('school_text') ?>
                            </option>
                            <?php else : ?>
                            <option value>Pilih Sekolah</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'user') : ?>
                    <div class="form-group col-6">
                        <select class="form-control select2 get-main-schools">
                            <?php if (Yii::$app->session->get('student_school_id')) : ?>
                            <option selected value="<?= Yii::$app->session->get('student_school_id') ?>">
                                <?= Yii::$app->session->get('student_school_text') ?>
                            </option>
                            <?php else : ?>
                            <option value>Pilih Sekolah</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image"
                                src="<?= Yii::$app->user->identity->photo ?? Yii::$app->homeUrl . "theme/stisla/assets/img/avatar/avatar-1.png" ?>"
                                class="rounded-circle profile-widget-picture mt-n3" style="width:32px; height:32px">
                            <div class="d-sm-none d-lg-inline-block">Hi,
                                <?= Yii::$app->user->identity->username ?? "Annonym" ?></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?= Url::to(Yii::getAlias('@web') . (Yii::$app->user->identity->role == 'user' ? '/auth' : '/administrator') . '/profiles/me', true)
                                        ?>" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= Url::to(Yii::getAlias('@web') . '/logout', true) ?>" data-method="post"
                                class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html"><?= Yii::$app->setting->app('app_name') ?></a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index.html"><?= Yii::$app->setting->app('app_name_') ?></a>
                    </div>
                    <?= $this->render('menu') ?>
                    <div class="mt-4">&nbsp;</div>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <!-- breadcrumb -->
                    <div class="section-header">
                        <h1><?= $this->context->title ? $this->context->title : ucfirst(Yii::$app->controller->id) ?>
                        </h1>
                        <div class='section-header-breadcrumb'>
                            <?= $breadcrumb ?>
                        </div>
                    </div>
                    <!-- endbteadcrumb -->
                    <!-- main content -->
                    <?= $content ?>
                    <!-- end main content -->
                    <?php print_r(Yii::$app->session->get('user_grant')); ?>
                    <?php print_r(Yii::$app->user->identity->full_name); ?>
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; <?= date('Y') ?> <div class="bullet"></div> By <a
                        href="https://garudacyber.co.id/">Garuda Cyber Indonesia | Imron R</a>
                </div>
                <div class="footer-right">
                    1.0.0
                </div>
            </footer>
        </div>
    </div>
    <!-- <div id="loader" class="tw-bg-yellow-500 tw-h-1.5 tw-fixed tw-rounded-r-full tw-top-0 tw-z-[9999]">&nbsp; -->
    </div>
    <?php $this->endBody() ?>
    <?php
    Modal::begin([
        'title' => '<span id="modalTitle">Modal</span>',
        'centerVertical' => true,
        'id' => 'modal',
        'size' => 'modal-lg',
        'scrollable' => true,
    ]);
    echo '<div id="modalContent"></div>';
    Modal::end();
    ?>
</body>

<?php
$homeUrl = Yii::$app->homeUrl;
$mod = Yii::$app->controller->module->id;
$con = Yii::$app->controller->id;

$csrf = Yii::$app->request->getCsrfToken();
$js = <<< JS
$('document').ready(()=>{  

    // function isLoading({status="init"}){
    //     let max = 97;
    //     let total = 80;
    //     let running = 0;
    //     let waiting = 0;

    //     function getRandomArbitrary(min, max) {
    //         return Math.random() * (max - min) + min;
    //     }

    //     if(status=="init"){
    //         $('#loader').show('slow');
    //         const isLoading = setInterval(function(){
    //             let random = getRandomArbitrary(0, 15);
    //             running = running + random;
    //             if(running>=total){
    //                 running = total;
    //                 if(waiting>=13 && waiting%3==0){
    //                     let random2 = getRandomArbitrary(0.0, 0.5);
    //                     total +=random2;
    //                 }
    //                 waiting++;
    //             }
    //             if(running>=max){
    //                 running = max;
    //                 clearInterval(isLoading)
    //             }
    //             $('#loader').css({'width':running+'%'})
    //         }, 70)
    //     }
    //     if(status=="finish"){
    //         console.log("ok")
    //         clearInterval(isLoading)
    //         $('#loader').css({'width':'100%'})
    //         setTimeout(function(){
    //             // $('#loader').hide('slow');
    //             console.log("siaoo")
    //         }, 1000);
    //     }
    // }

    var showNotif = 0;

    $('a').click((e)=>{
        if(!window.navigator.onLine){
            e.preventDefault();
            return false;
        }
        return true;
    })

    $('button').click((e)=>{
        if(!window.navigator.onLine){
            e.preventDefault();
            return false;
        }
        return true;
    })

    setInterval(function(){
        if(!window.navigator.onLine){
            if(showNotif==0){
                swal({
                    html:'<div>'+
                    '<img src="'+baseUrl+'icons/no-network.svg" />'+
                    '<p style="margin-bottom:0px;">Your Connection is Down!</p>'+
                    '<small>If you are in a form, please wait till connection back for safe the data!</small>'+
                    '</div>',
                    showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'Understood!',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showCancelButton: false
                }).then(function (){
                    if(!window.navigator.onLine){
                        $.notify("Oups, your connection still down!", 'error')
                    }
                    else{
                        $.notify("Yey, your connection is back!", 'success')
                    }
                    showNotif = 0;
                })
                $('.swal2-close').hide();
                showNotif = 1;
            }
        }
        else{
            if(showNotif==1){
                $('.swal2-confirm').trigger('click');
            }
        }
    }, 1000);

    let start = '';
    let isInterval = '';
    $(document).on('pjax:beforeSend', function(){
        start = Date.now();
    });
    $(document).on('pjax:send', function(){
        isInterval = setInterval(function(){
            let now = Date.now();
            if((now - start) >=1500){
                $.notify('Still working...', { 
                    className: 'info',
                    autoHide: false,
                    clickToHide: false
                });
                clearInterval(isInterval)
            }
        },50)
    });
    $(document).on('pjax:success', function(){
        $('.notifyjs-wrapper').trigger('notify-hide');
        clearInterval(isInterval)

    });
    $(document).on('pjax:error', function(){
        $('.notifyjs-wrapper').trigger('notify-hide');
        clearInterval(isInterval)
    });
    $(document).on('pjax:popstate', function(){
        document.referrer;
    });

    $('.get-main-schools').select2({
        ajax: {
            url: '$homeUrl'+'site/get-schools',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term
                }
                return query;
            }
        }
    });

    $('.get-main-schools').on('select2:select', function (e) {
        let id = $('.get-main-schools').val();
        let text = $('.get-main-schools').select2('data')[0]['text'];
        $.ajax({
            url: baseUrl+'site/set-schools',
            type: 'POST',
            data: {
                id: id,
                text: text,
                _csrf: _csrf
            },
            success:function(res){
                if(res){
                    swal(
                        'Success',
                        'Berhasil Memilih Sekolah!',
                        'success'
                    ).then((e)=>{
                        location.reload();
                    });
                }else{
                    swal(
                        'Error',
                        'Gagal Memilih Sekolah!',
                        'error'
                    );
                }
            }
        })
    });
});
JS;
$this->registerJs($js);
$this->registerJsVar('baseUrl', Yii::$app->homeUrl);
$this->registerJsVar('module', Yii::$app->controller->module->id);
$this->registerJsVar('controller', Yii::$app->controller->id);
$this->registerJsVar('_csrf', Yii::$app->request->csrfToken);
?>

</html>
<?php $this->endPage() ?>