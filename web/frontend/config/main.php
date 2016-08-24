<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'Beautydate',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'language' => 'es-ES',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'returnUrl' => ['site/inicio'],
            'loginUrl' => ['site/inicio'],
        ],
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/metronic/views'],
                'baseUrl' => '@web/themes/metronic',
                ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['paypal'],
                    'logFile' => '@runtime/logs/paypal.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'session' => [
            'name' => 'FRONTENDID',
            'savePath' => '@runtime/session_tmp',
        ],
        'urlManagerFront' => [
            'class' => 'yii\web\UrlManager',

        ],

        'i18n' => [
            'translations' => [
                'frontend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    /*'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],*/
                ],
                'common*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/../common/messages',
                ],
            ],
        ],
    ],
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['login','error','signup','inicio','request-password-reset', 'reset-password',
                    'check-your-mail', 'chg-pass-success', 'paypal-notification', 'rresponse', 'testrecurrent','useterms','privacy', 'ipn'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],

        ]
    ],
    'params' => $params,
];
