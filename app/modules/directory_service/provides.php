<?php

return array(
    'client_tabs' => array(
        'directory-tab' => array('view' => 'directory_tab', 'i18n' => 'directory_service.title'),
    ),
    'listings' => array(
        'directoryservice' => array('view' => 'directoryservice_listing', 'i18n' => 'directory_service.title'),
    ),
    'widgets' => array(
        array('view' => 'bound_to_ds_widget'),
        array('view' => 'modified_computernames_widget'),
    ),
    'reports' => array(
        'directory_service' => array('view' => 'directory_service_report', 'i18n' => 'directory_service.report'),
    ),
);
