<?php

return array(
    'client_tabs' => array(
        'power-tab' => array('view' => 'power_tab', 'i18n' => 'client.tab.power'),
        'battery-tab' => array('view' => 'battery_tab', 'i18n' => 'power.battery'),
    ),
    'listings' => array(
        'batteries' => array('view' => 'batteries_listing'),
        'power' => array('view' => 'power_listing'),
    ),
    'widgets' => array(
        array('view' => 'power_battery_condition_widget'),
        array('view' => 'power_battery_health_widget'),
    ),
    'reports' => array(
        'power' => array('view' => 'power'),
    ),
);
