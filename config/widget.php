<?php

return [
    'search_paths' => mr_env('WIDGET_SEARCH_PATHS', [local_conf('views/widgets')]),

    // These widgets are built-in and should not be changed unless you know what you are doing.
    'core' => [
        'unknown_widget' => config('_munkireport.view_path') . 'widgets/unknown_widget.php',
        'spacer' => config('_munkireport.view_path') . 'widgets/spacer_widget.php',
        'client' => resource_path('views') . '/reportdata/client_widget.php',
        'registered_clients' => resource_path('views') . '/reportdata/registered_clients_widget.php',
        'uptime' => resource_path('views') . '/reportdata/uptime_widget.php',
        'duplicated_computernames' => conf('view_path') . 'machine/duplicated_computernames_widget.yml',
        'hardware_basemodel' => conf('view_path') . 'machine/hardware_basemodel_widget.yml',
        'hardware_model' => conf('view_path') . 'machine/hardware_model_widget.yml',
        'hardware_type' => conf('view_path') . 'machine/hardware_type_widget.yml',
        'installed_memory' => conf('view_path') . 'machine/installed_memory_widget.yml',
        'memory' => conf('view_path') . 'machine/memory_widget.yml',
        'new_clients' => conf('view_path') . 'machine/new_clients_widget.yml',
        'os' => conf('view_path') . 'machine/os_widget.yml',
        'osbuild' => conf('view_path') . 'machine/osbuild_widget.yml',
    ]
];
