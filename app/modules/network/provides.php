<?php

return array(
    'client_tabs' => array(
        'network-tab' => array('view' => 'network_tab', 'i18n' => 'client.tab.network', 'badge' => 'network-cnt'),
    ),
    'listings' => array(
        array('view' => 'network_listing'),
    ),
    'widgets' => array(
        array('view' => 'network_location_widget'),
        array('view' => 'network_vlan_widget'),
    ),
);
