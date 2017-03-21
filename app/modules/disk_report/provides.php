<?php

return array(
    'client_tabs' => array(
        'storage-tab' => array('view' => 'storage_tab', 'i18n' => 'disk_report.storage'),
    ),
    'listings' => array(
        'disk' => array('view' => 'disk_listing', 'i18n' => 'disk_report.storage'),
    ),
    'widgets' => array(
        array('view' => 'disk_report_widget'),
        array('view' => 'filevault_widget'),
        array('view' => 'smart_status_widget'),
        array('view' => 'disk_type_widget'),
    ),
    'reports' => array(
        'storage' => array('view' => 'storage_report', 'i18n' => 'disk_report.report'),
    ),
);
