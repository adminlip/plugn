&lt;?php

$env = static function (string $name, string $default = ''): string {
    $value = getenv($name);
    return $value === false ? $default : $value;
};

return [
  'apiEndpoint' => 'http://localhost/~Saoud/plugn/plugn-yii2/api/web',
  'frontendUrl' => 'http://localhost/~Saoud/plugn/plugn-yii2/frontend/web',
  'dashboardAppUrl' => 'https://dash.staging.plugn.io',
    "crmAppUrl"  => 'https://crm.staging.plugn.io',
  'dashboardCookieDomain' => 'dash.dev.plugn.io',
  'newDashboardAppUrl' => 'https://dash.dev.plugn.io',
  'oneSignalStoreAPPID' => '',
  'oneSignalStoreAPIKey' => '',
  'oneSignalAgentAPPID' => $env('PLUGN_ONESIGNAL_AGENT_APP_ID'),
  'oneSignalAgentAPIKey' => $env('PLUGN_ONESIGNAL_AGENT_API_KEY'),
  'currencylayer_api_key' => $env('PLUGN_CURRENCYLAYER_API_KEY')
];