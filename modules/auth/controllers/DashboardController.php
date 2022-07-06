<?php

namespace app\modules\auth\controllers;

use Yii;
use yii\filters\{
    AccessControl
};
use yii\web\{
    ForbiddenHttpException,
};

class DashboardController extends \yii\web\Controller
{
    public $title = "Dashboard";

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        if (Yii::$app->users->can([])) :
            return $this->render('index');
        endif;
        throw new ForbiddenHttpException("You Can't Access This Page");
    }
}