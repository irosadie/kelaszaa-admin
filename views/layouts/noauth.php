<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\StislaNoAuthAsset;
use yii\bootstrap4\Html;

StislaNoAuthAsset::register($this);
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
<!-- done -->

<body>
    <?php $this->beginBody() ?>
    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 order-lg-2 min-vh-100 order-2 bg-white">
                    <div class="p-4 m-3">
                        <img src="<?= Yii::$app->homeUrl ?>img/logo.png" alt="logo" height="100" class="mb-5 mt-2">
                        <h4 class="text-dark font-weight-bold">DANA BOS</h4>
                        <p class="text-muted">Before you get started, you must login or register if you don't already
                            have an account.</p>
                        <?= $content ?>
                        <div class="text-center mt-5 text-small">
                            Copyright &copy; <?= date('Y') ?> By PT. Garuda Cyber Indonesia
                            <div class="mt-2">
                                <a href="#">baim</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-1 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
                    data-background="<?= Yii::$app->homeUrl ?>theme/stisla/assets/img/unsplash/login-bg.jpg">
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <h1 class="mb-2 display-4 font-weight-bold" id="greetings">Good Morning!</h1>
                                <h5 class="font-weight-normal text-muted-transparent">Pekanbaru, Indonesia</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->endBody() ?>
</body>

<?php
$js = <<< JS
$('document').ready(()=>{    
    const personalGreeting = () => {
        const hour = new Date().getHours();
        const dayParts = [6, 12, 18, 24];
        const greetings = ['Good Night!', 'Good Morning!', 'Good Afternoon!', 'Good Evening!'];

        let i = 0;
        const greet = () => {
            if (hour < dayParts[i]) {
                return greetings[i];
            }

            i++;
            return greet();
        };
        return greet();
    };
    $('#greetings').html(personalGreeting());

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

    hasClick = false
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
                    confirmButtonText: 'Understood!',
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
                hasClick = false
            }
        }
        else{
            if(showNotif==1 && !hasClick){
                $('.swal2-confirm').trigger('click');
                hasClick = true
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