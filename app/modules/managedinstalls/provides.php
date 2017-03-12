<?php

return array(
    'listings' => array(
        'managed_installs' => array('view' => 'managed_installs_listing', 'i18n' => 'managedinstalls.title'),
    ),
    'widgets' => array(
        array('view' => 'get_failing_widget'),
        array('view' => 'pending_apple_widget'),
        array('view' => 'pending_munki_widget'),
        array('view' => 'pending_widget'),
        array('view' => 'appusage_widget'),

    ),
    'reports' => array(
        array('view' => 'pkg_stats', 'i18n' => 'managedinstalls.installratio_report'),
    ),
);
