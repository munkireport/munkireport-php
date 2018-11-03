<?php

return array(
    'client_tabs' => array(
        'extensions-tab' => array('view' => 'extensions_tab', 'i18n' => 'extensions.clienttab', 'badge' => 'extensions-cnt'),
    ),
    'listings' => array(
        'extensions' => array('view' => 'extensions_listing', 'i18n' => 'extensions.clienttab'),
    ),
    'widgets' => array(
        'extensions' => array('view' => 'extensions_widget'),
        'extensions_developer' => array('view' => 'extensions_developer_widget'),
        'extensions_teamid' => array('view' => 'extensions_teamid_widget'),       
    ),
    'reports' => array(
        'extensions' => array('view' => 'extensions_report', 'i18n' => 'extensions.report'),
    ),
);
