<?php

return array(
    'client_tabs' => array(
      'sentinelone' => array('view' => 'sentinelone_client_tab', 'i18n' => 'sentinelone.client_tab'),
    ),
    'listings' => array(
        'sentinelone' => array('view' => 'sentinelone_listing', 'i18n' => 'sentinelone.sentinelone'),
    ),
    'widgets' => array(
        'agent_running' => array('view' => 'agent_running_widget'),
        'active_threats' => array('view' => 'active_threats_widget'),
        'enforcing_security' => array('view' => 'enforcing_security_widget'),
        'self_protection' => array('view' => 'self_protection_widget'),
        'version' => array('view' => 'version_widget'),
        'mgmt_url' => array('view' => 'mgmt_url_widget')
    ),
    'reports' => array(
        'sentinelone' => array('view' => 'sentinelone_report', 'i18n' => 'sentinelone.report'),
    ),
);
