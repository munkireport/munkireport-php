<?php

return array(
    'client_tabs' => array(
        'network-tab' => array('view' => 'network_tab', 'i18n' => 'network.network', 'badge' => 'network-cnt'),
    ),
    'listings' => array(
        'network' => array('view' => 'network_listing', 'i18n' => 'network.network'),
    ),
    'widgets' => array(
        array('view' => 'network_location_widget'),
        array('view' => 'network_vlan_widget'),
    ),
    'reports' => array(
        'network' => array('view' => 'network', 'i18n' => 'network.report'),
    ),
);
