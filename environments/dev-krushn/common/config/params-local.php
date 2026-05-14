&lt;?php

$env = static function (string $name, string $default = ''): string {
    $value = getenv($name);
    return $value === false ? $default : $value;
};

return [
  'apiEndpoint' => 'http://localhost:8888/plugn/api/web',
  'frontendUrl' => 'http://localhost:8888/plugn/frontend/web',
  'dashboardAppUrl' => 'http://localhost:8100',
    "crmAppUrl"  => 'http://localhost:8100',
  'dashboardCookieDomain' => 'localhost',
    'newDashboardAppUrl' => 'https://dash.dev.plugn.io',
  'oneSignalStoreAPPID' => '',
    'oneSignalStoreAPIKey' => '',
    'oneSignalAgentAPPID' => $env('PLUGN_ONESIGNAL_AGENT_APP_ID'),
    'oneSignalAgentAPIKey' => $env('PLUGN_ONESIGNAL_AGENT_API_KEY'),
    'currencylayer_api_key' => $env('PLUGN_CURRENCYLAYER_API_KEY')
];