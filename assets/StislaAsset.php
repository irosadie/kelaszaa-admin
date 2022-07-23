<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StislaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
        'css/tailwind.css',

        // CDN libs
        ['https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', 'integrity' => "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T", 'crossorigin' => "anonymous"],
        ['https://use.fontawesome.com/releases/v5.7.2/css/all.css', 'integrity' => "sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr", 'crossorigin' => "anonymous"],

        // Template CSS
        'theme/stisla/assets/css/style.css',
        'theme/stisla/assets/css/components.css',
        'plugin/node/filepond/filepond.css',
        'plugin/node/select2/css/select2.min.css',
        'plugin/node/bootstrap-tagsinput/bootstrap-tagsinput.css',
        'plugin/node/bootstrap-daterangepicker/daterangepicker.css',
        'plugin/node/sweetalert2/sweetalert2.min.css',
    ];
    public $js = [
        // CDN libs
        ['https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', 'integrity' => "sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1", 'crossorigin' => "anonymous"],
        ['https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', 'integrity' => "sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM", 'crossorigin' => "anonymous"],

        'https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js',
        'https://cdn.tiny.cloud/1/88hi5czqr438a9kuu2no7o552qdmwed157y67zjakqlu0ete/tinymce/6/tinymce.min.js',

        '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js',

        "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js",


        // Template JS
        'theme/stisla/assets/js/stisla.js',
        'theme/stisla/assets/js/scripts.js',

        'plugin/node/filepond/filepond.js',
        'plugin/node/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.js',
        'plugin/node/bootstrap-daterangepicker/daterangepicker.js',
        'plugin/node/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'plugin/node/select2/js/select2.full.min.js',
        'plugin/node/sweetalert2/sweetalert2.min.js',
        // 'plugin/node/tinymce/tinymce.min.js',
        // 'plugin/node/tinymce-jquery/tinymce-jquery.min.js',

        // Plugin JS
        'plugin/notify/notify.min.js',
        'plugin/mask-currency/mask-currency.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}