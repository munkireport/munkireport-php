<?php

return array(
    'client_tabs' => array(
        'printer-tab' => array('view' => 'printer_tab', 'i18n' => 'client.tab.printers', 'badge' => 'printer-cnt'),
    ),
    'listings' => array(
        array('view' => 'printers_listing'),
    ),
    'widgets' => array(
        array('view' => 'printer_widget'),
    ),
    'reports' => array(
        'printer' => array('view' => 'printer_report', 'i18n' => 'nav.reports.printer'),
    ),
);
