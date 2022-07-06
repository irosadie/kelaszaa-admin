<?php

namespace app\modules\auth;

use app\utils\helper\Helper;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Auth module definition class
 */
class Auth extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\auth\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Helper::userLevel();
    }
}