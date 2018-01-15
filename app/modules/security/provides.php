<?php

return array(
    'listings' => array(
        'security' => array('view' => 'security_listing', 'i18n' => 'security.security'),
    ),
    'widgets' => array(
        'firmwarepw' => array('view' => 'security::firmwarepw_widget'),
        'gatekeeper' => array('view' => 'security::gatekeeper_widget'),
        'sip' => array('view' => 'security::sip_widget'),
        'firewall_state' => array('view' => 'security::firewall_state_widget'),
        'skel_state' => array('view' => 'security::skel_state_widget'),
    ),
    'reports' => array(
        'security' => array('view' => 'security', 'i18n' => 'security.report'),
    ),
);
