<?php
$params = require __DIR__ . '/params.php';

$path = YII_ENV_DEV ? "db-local" : "db";

$db_kelaszaa = require __DIR__ . "/$path/db_kelaszaa.php";

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ],
    'modules' => [
        'auth' => [
            'class' => 'app\modules\auth\Auth',
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'k3lasZ44Val1d4ti0nByB4im',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\identities\Users',
            'enableAutoLogin' => true,
        ],
        'encryptor' => [
            'class' => \app\utils\encrypt\Encryptor::class
        ],
        'setting' => [
            'class' => \app\utils\setting\SettingHelper::class
        ],
        'users' => [
            'class' => \app\utils\rbac\CustomRbac::class
        ],
        'logs' => [
            'class' => \app\utils\logs\Logs::class
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'tls',
                'host' => 'smtp.gmail.com',
                'port' => '587',
                'username' => 'te3ja4@gmail.com',
                'password' => 'jum06Mar20',
            ]
        ],
        // 'authManager' => [
        //     'class' => 'app\utils\dbManagerHelper\DbManagerHelper'
        // ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db_kelaszaa,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<alias:login>' => 'site/login',
                '<alias:logout>' => 'site/logout',
                '<alias:not-allowed>' => 'site/not-allowed',
            ],
        ],
    ],
    // 'as access' => [
    //     'class' => 'mdm\admin\components\AccessControl',
    //     'allowActions' => [
    //         'site/*'
    //     ],
    // ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1']
    ];
}

return $config;