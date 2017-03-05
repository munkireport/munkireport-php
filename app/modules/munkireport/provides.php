<?php

return array(
    'client_tabs' => array(
        'munki' => array('view' => 'client_munki_tab', 'i18n' => 'client.tab.munki'),
    ),
    'listings' => array(
        'munki' => array('view' => 'munki_listing'),
    ),
    'widgets' => array(
        array('view' => 'manifests_widget'),
        array('view' => 'munki_versions_widget'),
        array('view' => 'munki_widget'),
    ),
    'reports' => array(
        'munki' => array('view' => 'munki'),
    ),
);
