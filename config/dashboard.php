<?php

return [
    'search_paths' => mr_env('DASHBOARD_SEARCH_PATHS', [local_conf('dashboards')]),
    'template' => mr_env('DASHBOARD_TEMPLATE', 'dashboard/dashboard'),
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
