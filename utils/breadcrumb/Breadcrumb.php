<?php

namespace app\utils\breadcrumb;
use Yii;

class Breadcrumb
{
    public static function generateBreadcrumbs($router, $class='', $connector=''){
        $route = explode('/', $router);
        $baseUrl = Yii::$app->homeUrl;
        //use modules
        if(count($route)==3):
            $module = Yii::$app->controller->module->id;
            $isRoute[0] = "<div class='$class'><a href='{$baseUrl}{$module}/dashboard'>{$route[0]}</a></div>";
            $isRoute[1] = "<div class='$class'><a href='{$baseUrl}{$module}/{$route[1]}/index'>{$route[1]}</a></div>";
            $isRoute[2] = "<div class='$class'>{$route[2]}</div>";
        endif;

        //wo modules
        if(count($route)==2):
            $isRoute[0] = "<div class='$class'><a href='{$baseUrl}{$router[0]}index'>{$route[0]}</a></div>";
            $isRoute[1] = "<div class='$class'>$route[1]</div>";
        endif;

        $isRouter = implode("$connector", $isRoute);
        return $isRouter;
    }
}