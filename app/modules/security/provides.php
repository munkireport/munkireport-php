<?php

return array(
    'listings' => array(
        'security' => array('view' => 'security_listing'),
    ),
    'widgets' => array(
        array('view' => 'firmwarepw_widget'),
        array('view' => 'gatekeeper_widget'),
        array('view' => 'sip_widget'),
    ),
    'reports' => array(
        'security' => array('view' => 'security'),
    ),
);
