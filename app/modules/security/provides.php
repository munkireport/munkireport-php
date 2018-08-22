<?php

return array(
    'listings' => array(
        'security' => array('view' => 'security_listing', 'i18n' => 'security.security'),
    ),
    'widgets' => array(
        'firmwarepw' => array('view' => 'firmwarepw_widget', 'icon' => 'fa-lock'),
        'gatekeeper' => array('view' => 'gatekeeper_widget', 'icon' => 'fa-lock'),
        'sip' => array('view' => 'sip_widget', 'icon' => 'fa-lock'),
        'firewall_state' => array('view' => 'firewall_state_widget', 'icon' => 'fa-fire'),
        'skel_state' => array('view' => 'skel_state_widget', 'icon' => 'fa-lock'),
        'ssh_state' => array('view' => 'ssh_state_widget', 'icon' => 'fa-terminal'),
    ),
    'reports' => array(
        'security' => array('view' => 'security', 'i18n' => 'security.report'),
    ),
);
