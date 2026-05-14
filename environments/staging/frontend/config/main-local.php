<?php

[$env, $requiredEnv] = require __DIR__ . '/../../common/config/env-local.php';

return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $requiredEnv('STAGING_FRONTEND_COOKIE_VALIDATION_KEY'),
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => $env('STAGING_FRONTEND_BASE_URL', 'https://dashboard.staging.plugn.io'),
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'session' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => $requiredEnv('STAGING_REDIS_HOSTNAME'),
                'port' => $env('STAGING_REDIS_PORT', 6379),
                'database' => $env('STAGING_FRONTEND_REDIS_DATABASE', 9),
            ]
        ],
    ],
];

// Debug and GII modules are disabled in staging for security
// Do not enable these modules in a staging/production environment
