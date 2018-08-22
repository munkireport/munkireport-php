<?php

return array(
    'listings' => array(
        'managed_installs' => array('view' => 'managed_installs_listing', 'i18n' => 'managedinstalls.title'),
    ),
    'widgets' => array(
        'get_failing' => array('view' => 'get_failing_widget', 'icon' => 'fa-warning'),
        'pending_apple' => array('view' => 'pending_apple_widget', 'icon' => 'fa-apple'),
        'pending_munki' => array('view' => 'pending_munki_widget', 'icon' => 'fa-shopping-cart'),
        'pending' => array('view' => 'pending_widget', 'icon' => 'fa-moon-o'),
    ),
    'reports' => array(
        array('view' => 'pkg_stats', 'i18n' => 'managedinstalls.installratio_report'),
    ),
);
