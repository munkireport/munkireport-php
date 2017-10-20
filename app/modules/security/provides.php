<?php

return array(
    'listings' => array(
        'security' => array('view' => 'security_listing', 'i18n' => 'security.security'),
    ),
    'widgets' => array(
        'firmwarepw' => array('view' => 'firmwarepw_widget'),
        'gatekeeper' => array('view' => 'gatekeeper_widget'),
        'sip' => array('view' => 'sip_widget'),
        'firewall_state' => array('view' => 'firewall_state_widget'),
        'skel_state' => array('view' => 'skel_state_widget'),
    ),
    'reports' => array(
        'security' => array('view' => 'security', 'i18n' => 'security.report'),
    ),
);
