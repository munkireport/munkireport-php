<?php

return array(
    'client_tabs' => array(
        'directory-tab' => array('view' => 'directory_tab', 'i18n' => 'client.tab.ds'),
    ),
    'listings' => array(
        array('view' => 'directoryservice_listing'),
    ),
    'widgets' => array(
        array('view' => 'bound_to_ds_widget'),
        array('view' => 'modified_computernames_widget'),
    ),
    'reports' => array(
        'directory_service' => array('view' => 'directory_service_report', 'i18n' => 'nav.reports.directoryservice'),
    ),
);
