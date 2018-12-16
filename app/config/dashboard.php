<?php

return [
    'search_paths' => env('DASHBOARD_SEARCH_PATHS', [local_conf('dashboards')]),
    'template' => env('DASHBOARD_TEMPLATE', 'dashboard/dashboard'),
    'default_layout' => [
        [
          'client' => [], 
          'messages' => [],
        ],
        [
          'new_clients' => [], 
          'pending_apple' => [],
          'pending_munki' => [],
        ],
        [
          'munki' => [],
          'disk_report' => [],
          'uptime' => [],
        ],
    ],
];
