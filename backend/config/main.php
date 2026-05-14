<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Plugn',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'trustedHosts' => [
                '10.0.0.0/8',      // Railway internal network
                '172.16.0.0/12',   // Docker network
                '192.168.0.0/16',  // Local network
            ],
           /* 'headers' => [
                'X-Forwarded-Proto' => 'https',
            ],*/
        ],
        'assetManager' => [
          'linkAssets' => true,
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'auth0' => [
            'class' => 'common\components\Auth0',
            'domain' => getenv('AUTH0_DOMAIN') ?: 'bawes.us.auth0.com',
            'clientId' => getenv('AUTH0_CLIENT_ID') ?: '',
            'clientSecret' => getenv('AUTH0_CLIENT_SECRET') ?: '',
            'cookieSecret' => getenv('AUTH0_COOKIE_SECRET') ?: '',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => getenv('GOOGLE_CLIENT_ID') ?: '',
                    'clientSecret' => getenv('GOOGLE_CLIENT_SECRET') ?: '',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            'baseUrl' => 'https://admin.plugn.io',
            'hostInfo' => 'https://admin.plugn.io',
            'rules' => [
                'site/auth' => 'site/auth',
            ],
        ],
    ],
    'params' => $params,
];
