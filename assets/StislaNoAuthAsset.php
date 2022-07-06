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
class StislaNoAuthAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/tailwind.css',
        ["https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css", "integrity" => "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T", "crossorigin" => "anonymous"],
        ["https://use.fontawesome.com/releases/v5.7.2/css/all.css", "integrity" => "sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr", "crossorigin" => "anonymous"],
        "https://code.jquery.com/jquery-3.3.1.min.js",
        "theme/stisla/assets/css/style.css",
        "@webroot/node_modules/bootstrap-social/bootstrap-social.css",
        "theme/stisla/assets/css/components.css",
    ];
    public $js = [
        ["https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js", "integrity" => "sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1", "crossorigin" => "anonymous"],
        ["https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js", "integrity" => "sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM", "crossorigin" => "anonymous"],
        "https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js",
        "theme/stisla/assets/js/stisla.js",
        "theme/stisla/assets/js/scripts.js",
        "theme/stisla/assets/js/custom.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}