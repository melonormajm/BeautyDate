<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [

    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'rules' => [
                    'notify/<id:\d+>'=> 'site/rresponse',
            ]
        ]
    ]
];
