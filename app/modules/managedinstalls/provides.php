<?php

return array(
    'listings' => array(
        'managed_installs' => array('view' => 'managed_installs_listing', 'i18n' => 'managedinstalls.title'),
    ),
    'widgets' => array(
        'get_failing' => array('view' => 'managedinstalls::get_failing_widget'),
        'pending_apple' => array('view' => 'managedinstalls::pending_apple_widget'),
        'pending_munki' => array('view' => 'managedinstalls::pending_munki_widget'),
        'pending' => array('view' => 'managedinstalls::pending_widget'),
        'appusage' => array('view' => 'managedinstalls::appusage_widget'),

    ),
    'reports' => array(
        array('view' => 'pkg_stats', 'i18n' => 'managedinstalls.installratio_report'),
    ),
);
