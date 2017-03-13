<?php

return array(
    'client_tabs' => array(
        'munki' => array('view' => 'client_munki_tab', 'i18n' => 'munkireport.munki'),
    ),
    'listings' => array(
        'munki' => array('view' => 'munki_listing', 'i18n' => 'munkireport.munki'),
    ),
    'widgets' => array(
        array('view' => 'manifests_widget'),
        array('view' => 'munki_versions_widget'),
        array('view' => 'munki_widget'),
    ),
    'reports' => array(
        'munki' => array('view' => 'munki', 'i18n' => 'munkireport.managedsoftware'),
    ),
);
