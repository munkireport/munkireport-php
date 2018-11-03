<?php

return array(
    'client_tabs' => array(
        'munki' => array('view' => 'client_munki_tab', 'i18n' => 'munkireport.munki'),
    ),
    'listings' => array(
        'munki' => array('view' => 'munki_listing', 'i18n' => 'munkireport.munki'),
    ),
    'widgets' => array(
        'manifests' => array('view' => 'manifests_widget'),
        'munki_versions' => array('view' => 'munki_versions_widget'),
        'munki' => array('view' => 'munki_widget'),
    ),
    'reports' => array(
        'munki' => array('view' => 'munki', 'i18n' => 'munkireport.managedsoftware'),
    ),
);
