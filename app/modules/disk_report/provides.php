<?php

return array(
    'client_tabs' => array(
        'storage-tab' => array('view' => 'storage_tab', 'i18n' => 'disk_report.storage'),
    ),
    'listings' => array(
        'disk' => array('view' => 'disk_listing', 'i18n' => 'disk_report.storage'),
    ),
    'widgets' => array(
        'disk_report' => array('view' => 'disk_report_widget', 'icon' => 'fa-hdd-o'),
        'filevault' => array('view' => 'filevault_widget', 'icon' => 'fa-lock'),
        'smart_status' => array('view' => 'smart_status_widget', 'icon' => 'fa-exclamation-circle'),
        'disk_type' => array('view' => 'disk_type_widget', 'icon' => 'fa-hdd-o'),
        'filesystem_type' => array('view' => 'filesystem_type_widget', 'icon' => 'fa-hdd-o'),
    ),
    'reports' => array(
        'storage' => array('view' => 'storage_report', 'i18n' => 'disk_report.report'),
    ),
);
