<?php

namespace app\utils\rbac;

use app\utils\helper\Helper;
use yii\helpers\ArrayHelper;
use Yii;

class CustomRbac
{
    public function can($levels = [], $schools = [])
    {
        if (count($levels) == 0)
            return true;
        $check = Helper::in_arrays($levels, Yii::$app->session->get("user_grant")['levels'] ?? []);
        if ($check) :
            if ($schools) :
                return Helper::in_arrays($schools, ArrayHelper::getColumn(Yii::$app->session->get('user_grant')['schools'], 'id'));
            else :
                return true;
            endif;
        endif;
        return false;
    }
}