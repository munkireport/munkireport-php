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
        'extensions_codesign' => array('view' => 'extensions_codesign_widget'),
    ),
    'reports' => array(
        'extensions' => array('view' => 'extensions_report', 'i18n' => 'extensions.report'),
    ),
);
