&lt;?php

$env = static function (string $name, string $default = ''): string {
    $value = getenv($name);
    return $value === false ? $default : $value;
};

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=plugn_test',
            'username' => 'bawes',
            'password' => 'passw0rd',
            'charset' => 'utf8',
        ],
        'mailer' => [
                 'class' => \yii\symfonymailer\Mailer::class,
                 'viewPath' => '@common/mail',
                 // send all mails to a file by default. You have to set
                 // 'useFileTransport' to false and configure a transport
                 // for the mailer to send real emails.
                 'useFileTransport' => true,
        ],
        'eventManager' => [
            'class' => 'common\components\EventManager',
            "sqsRagion" => $env('PLUGN_SQS_REGION', 'eu-west-2'),
            "sqsKey" => $env('PLUGN_SQS_KEY'),
            "sqsSecret" => $env('PLUGN_SQS_SECRET'),
            "sqsQueue" => $env('PLUGN_SQS_QUEUE', '438663597141/PlugnDev')
        ],
        'walletManager' => [
            'class' => 'common\components\WalletManager',
            'apiKey' => $env('PLUGN_WALLET_API_KEY'),
            'apiEndpoint' => $env('PLUGN_WALLET_API_ENDPOINT', 'http://localhost/wallet/webhook/web/v1'),
            'companyWalletUserID' => $env('PLUGN_COMPANY_WALLET_USER_ID')
        ],
        'gpt' => [
            'class' => 'common\components\GptComponent',
            'token' => $env('PLUGN_GPT_TOKEN'),
            'apiEndpoint' => $env('PLUGN_GPT_API_ENDPOINT', 'http://localhost:8083/')
        ],
        'resourceManager' => [
            'class' => 'common\components\S3ResourceManager',
            'region' => $env('PLUGN_S3_REGION', 'eu-west-2'),
            'key' => $env('PLUGN_S3_KEY'),
            'secret' => $env('PLUGN_S3_SECRET'),
            'bucket' => $env('PLUGN_S3_BUCKET', 'plugn-public-anyone-can-upload-24hr-expiry')
        ],

        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
            "destinationId" => null
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