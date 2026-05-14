<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'namespace' => 'common\fixtures',
            'templatePath' => '@common/fixtures/templates',
            'fixtureDataPath' => '@common/fixtures/data',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => getenv('REDIS_HOST') ?: 'localhost',
                'port' => getenv('REDIS_PORT') ?: 6379,
                'database' => getenv('REDIS_CACHE_DB') ?: 0,
                'password' => getenv('REDIS_PASSWORD') ?: null,
            ],
            'defaultDuration' => 86400,
            'keyPrefix' => 'plugn_',
        ],
    ],
    'params' => $params,
];
