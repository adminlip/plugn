<?php

[$env, $requiredEnv] = require __DIR__ . '/../../common/config/env-local.php';

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $requiredEnv('STAGING_BACKEND_COOKIE_VALIDATION_KEY'),
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => $env('STAGING_BACKEND_BASE_URL', '/~saoud/plugn/plugn-yii2/backend/web/'),
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
        'session' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => $requiredEnv('STAGING_REDIS_HOSTNAME'),
                'port' => $env('STAGING_REDIS_PORT', 6379),
                'database' => $env('STAGING_BACKEND_REDIS_DATABASE', 7),
            ]
        ],
    ],
];

// Debug and GII modules are disabled in staging for security
// Do not enable these modules in a staging/production environment

return $config;
