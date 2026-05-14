<?php

[$env, $requiredEnv] = require __DIR__ . '/../../common/config/env-local.php';

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $requiredEnv('STAGING_CRM_COOKIE_VALIDATION_KEY'),
        ],
    ],
];

// Debug and GII modules are disabled in staging for security
// Do not enable these modules in a staging/production environment

return $config;
