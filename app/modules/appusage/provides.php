<?php

return array(
    'client_tabs' => array(
        'appusage-tab' => array(
            'view' => 'appusage_tab',
            'i18n' => 'appusage.appusage',
            'view_controller' => 'AppusageTabController',
        ),
    ),
    'listings' => array(
        'appusage' => array('view' => 'appusage_listing', 'i18n' => 'appusage.title'),
    ),
    'widgets' => array(
        'app_usage' => array('view' => 'appusage::app_usage_widget'),
    ),
);
