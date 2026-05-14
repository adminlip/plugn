<?php

$env = static function (string $name, $default = null) {
    $value = getenv($name);
    return $value === false ? $default : $value;
};

$redisHost = $env('REDISHOST', $env('REDIS_HOST', 'redis'));
$redisPort = (int) $env('REDISPORT', $env('REDIS_PORT', 6379));
$redisDatabase = (int) $env('REDIS_DATABASE', 0);

return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IPzstcYT6LrNZ7AsUzf8Zz5XtEtX1',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://dashboard.plugn.io',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'session' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Session',
            'redis' => [
                'class' => 'yii\redis\Connection',
                'hostname' => $redisHost,
                'username' => $env('REDISUSER', $env('REDIS_USERNAME', 'default')),
                'password' => $env('REDISPASSWORD', $env('REDIS_PASSWORD', '')),
                'port' => $redisPort,
                'database' => $redisDatabase,
            ]
        ],
    ],
];
