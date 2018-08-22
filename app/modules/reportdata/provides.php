<?php

return array(
    'listings' => array(
        'clients' => array('view' => 'clients_listing', 'i18n' => 'client.clients'),
    ),
    'widgets' => array(
        'client' => array('view' => 'client_widget', 'icon' => 'fa-group'),
        'registered_clients' => array('view' => 'registered_clients_widget', 'icon' => 'fa-clock-o'),
        'uptime' => array('view' => 'uptime_widget', 'icon' => 'fa-power-off'),
    ),
    'reports' => array(
        'clients' => array('view' => 'clients', 'i18n' => 'client.report'),
    ),
);
