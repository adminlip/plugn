&lt;?php

$env = static function (string $name, string $default = ''): string {
    $value = getenv($name);
    return $value === false ? $default : $value;
};

return [
  'apiEndpoint' => 'https://api.plugn.io',
  'frontendUrl' => 'https://dash.plugn.io',
  'dashboardAppUrl' => 'https://dash.plugn.io',
  'newDashboardAppUrl' => 'https://dash.plugn.io',
    'crmAppUrl' => 'https://crm.plugn.io',
  'dashboardCookieDomain' => 'dash.plugn.io',
    'oneSignalStoreAPPID' => '',
    'oneSignalStoreAPIKey' => '',
    'oneSignalAgentAPPID' => $env('PLUGN_ONESIGNAL_AGENT_APP_ID'),
    'oneSignalAgentAPIKey' => $env('PLUGN_ONESIGNAL_AGENT_API_KEY'),
    'currencylayer_api_key' => $env('PLUGN_CURRENCYLAYER_API_KEY')
];