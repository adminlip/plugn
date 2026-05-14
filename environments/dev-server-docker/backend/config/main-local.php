<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IPzstcYT6LrNZ7AsUzf8Zz5XtEtX1',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/backend/web',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => getenv('REDIS_HOST') ?: 'redis',
                'port' => getenv('REDIS_PORT') ?: 6379,
                'database' => 6,
            ]
        ],
    ],
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}


return $config;
