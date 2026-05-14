&lt;?php

$env = static function (string $name, string $default = ''): string {
    $value = getenv($name);
    return $value === false ? $default : $value;
};

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=plugn',
            'username' => 'root',
            'password' => '12345',
            'charset' => 'utf8mb4',
            //'port' => 3307,
            // Enable Caching of Schema to Reduce SQL Queries
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 10, // 10 seconds
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'eventManager' => [
            'class' => 'common\components\EventManager',
            "sqsRagion" => $env('PLUGN_SQS_REGION', 'eu-west-2'),
            "sqsKey" => $env('PLUGN_SQS_KEY'),
            "sqsSecret" => $env('PLUGN_SQS_SECRET'),
            "sqsQueue" => $env('PLUGN_SQS_QUEUE', '438663597141/PlugnDev')
        ],
        'cache' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'redis',
                'port' => 6379,
                'database' => 2,
            ]
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'gpt' => [
            'class' => 'common\components\GptComponent',
            'token' => $env('PLUGN_GPT_TOKEN'),
            'apiEndpoint' => $env('PLUGN_GPT_API_ENDPOINT', 'http://localhost:8083/')
        ],
        'mailer' => [
                 'class' => \yii\symfonymailer\Mailer::class,
                 'viewPath' => '@common/mail',
                 // send all mails to a file by default. You have to set
                 // 'useFileTransport' to false and configure a transport
                 // for the mailer to send real emails.
                 'useFileTransport' => true,
        ],
        'walletManager' => [
            'class' => 'common\components\WalletManager',
            'apiKey' => $env('PLUGN_WALLET_API_KEY'),
            'apiEndpoint' => $env('PLUGN_WALLET_API_ENDPOINT', 'http://localhost/wallet/webhook/web/v1'),
            'companyWalletUserID' => $env('PLUGN_COMPANY_WALLET_USER_ID')
        ],
        'resourceManager' => [
            'class' => 'common\components\S3ResourceManager',
            'region' => $env('PLUGN_S3_REGION', 'eu-west-2'), // Bucket based in London
            'key' => $env('PLUGN_S3_KEY'),
            'secret' => $env('PLUGN_S3_SECRET'),
            'bucket' => $env('PLUGN_S3_BUCKET', 'plugn-uploads-dev-server'),
            /**
             * For Local Development, we access using key and secret
             * For Dev and Production servers, access is via server embedded IAM roles so no key/secret required
             *
             * You can access the bucket with:
             * https://plugn-uploads-dev-server.s3.amazonaws.com/
             * https://plugn-uploads-dev-server.s3.amazonaws.com/folderName/fileName.jpg
             */
        ],
        
        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
        ],
        'armadaDelivery' => [
            'keyToUse' => \common\components\ArmadaDelivery::USE_TEST_KEY,
        ],
        'mashkorDelivery' => [
            'class' => 'common\components\MashkorDelivery',
            'keyToUse' => \common\components\MashkorDelivery::USE_TEST_KEY,
        ],
        'githubComponent' => [
            'class' => 'common\components\GithubComponent',
            'branch' => 'develop'
        ],
        'apiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://api.dev.plugn.io',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ], 
        //microservices
        'blogManager' => [
            'class' => 'common\components\BlogManager',
            'apiEndpoint' => $env('PLUGN_BLOG_API_ENDPOINT', 'http://localhost:8080/v1'),
            'token' => $env('PLUGN_BLOG_TOKEN')
        ], 
        'agentApiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://agent.dev.plugn.io',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
    ],
];