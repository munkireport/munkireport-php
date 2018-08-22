<?php

return array(
    'client_tabs' => array(
      'sentinelone' => array('view' => 'sentinelone_client_tab', 'i18n' => 'sentinelone.client_tab'),
    ),
    'listings' => array(
        'sentinelone' => array('view' => 'sentinelone_listing', 'i18n' => 'sentinelone.sentinelone'),
    ),
    'widgets' => array(
        'agent_running' => array('view' => 'agent_running_widget', 'icon' => 'fa-check-circle'),
        'active_threats' => array('view' => 'active_threats_widget', 'icon' => 'fa-meh-o'),
        'enforcing_security' => array('view' => 'enforcing_security_widget', 'icon' => 'fa-shiel'),
        'self_protection' => array('view' => 'self_protection_widget', 'icon' => 'fa-fire-extinguisher'),
        'version' => array('view' => 'version_widget', 'icon' => 'fa-code'),
        'mgmt_url' => array('view' => 'mgmt_url_widget', 'icon' => 'fa-compass')
    ),
    'reports' => array(
        'sentinelone' => array('view' => 'sentinelone_report', 'i18n' => 'sentinelone.report'),
    ),
);
