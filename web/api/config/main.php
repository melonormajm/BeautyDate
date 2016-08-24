<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'name' => 'Beautydate Api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
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
        ],/*
        'urlManager' => [
            //'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            //'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    '<module:\w+>/<controller:\w+>' => '<module>/<controller>/mio',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ]
            ],
        ],*/
        'urlManagerFront' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://salones.beautydate.mx',
            'hostInfo' => 'salones.beautydate.mx',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->data = [
                    'success' => $response->isSuccessful,
                    'content' => is_array($response->data) && isset($response->data['name'])
                    && $response->data['name'] == 'Exception' ? $response->data['message']
                        : $response->data,
                ];
                $response->statusCode = 200;
            },
            'format' => \yii\web\Response::FORMAT_JSON,
        ],
    ],
    'params' => $params,
];



