<?php

namespace app\utils\setting;

use app\models\helpers\{
    Settings
};

class SettingHelper
{

    public function app($value)
    {
        $model = Settings::findOne(['value' => $value]);
        if ($model) :
            return $model->value_;
        endif;
        return NULL;
    }
}