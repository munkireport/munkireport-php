<?php

return array(
    'listings' => array(
        'clients' => array('view' => 'clients_listing', 'i18n' => 'client.clients'),
    ),
    'widgets' => array(
        array('view' => 'client_widget'),
        array('view' => 'registered_clients_widget'),
        array('view' => 'uptime_widget'),
    ),
    'reports' => array(
        'clients' => array('view' => 'clients', 'i18n' => 'client.report'),
    ),
);
