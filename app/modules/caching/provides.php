<?php

if (!conf('caching_show_legacy')) {

    return array(
        'client_tabs' => array(
            'caching-tab' => array('view' => 'caching_tab', 'i18n' => 'caching.client_tab_title'),
        ),
        'listings' => array(
            'caching' => array('view' => 'caching_listing', 'i18n' => 'caching.listing_title'),
        ),
        'widgets' => array(
            'caching' => array('view' => 'caching_widget', 'icon' => 'fa-database'),
            'caching_media' => array('view' => 'caching_media_widget', 'icon' => 'fa-music'),
            'caching_icloud' => array('view' => 'caching_icloud_widget', 'icon' => 'fa-cloud'),
            'caching_software' => array('view' => 'caching_software_widget', 'icon' => 'fa-cubes'),
            'caching_graph' => array('view' => 'caching_graph_widget', 'icon' => 'fa-database'),
            'caching_usage' => array('view' => 'caching_usage_widget', 'icon' => 'fa-database'),
            'caching_reachable_servers' => array('view' => 'caching_reachable_servers_widget', 'icon' => 'fa-hand-lizard-o'),
        ),
        'reports' => array(
            'caching' => array('view' => 'server', 'i18n' => 'caching.reporttitle'),
        ),
    );
} else { 
    
    return array(
        'client_tabs' => array(
            'caching-tab' => array('view' => 'caching_tab', 'i18n' => 'caching.client_tab_title'),
        ),
        'listings' => array(
            'caching' => array('view' => 'caching_listing', 'i18n' => 'caching.listing_title'),
            'caching_legacy' => array('view' => 'caching_legacy_listing', 'i18n' => 'caching.listing_title_legacy'),
        ),
        'widgets' => array(
            'caching' => array('view' => 'caching_widget', 'icon' => 'fa-database'),
            'caching_media' => array('view' => 'caching_media_widget', 'icon' => 'fa-music'),
            'caching_icloud' => array('view' => 'caching_icloud_widget', 'icon' => 'fa-cloud'),
            'caching_software' => array('view' => 'caching_software_widget', 'icon' => 'fa-cubes'),
            'caching_graph' => array('view' => 'caching_graph_widget', 'icon' => 'fa-database'),
            'caching_usage' => array('view' => 'caching_usage_widget', 'icon' => 'fa-database'),
            'caching_reachable_servers' => array('view' => 'caching_reachable_servers_widget', 'icon' => 'fa-hand-lizard-o'),
        ),
        'reports' => array(
            'caching' => array('view' => 'server', 'i18n' => 'caching.reporttitle'),
        ),
    );
}