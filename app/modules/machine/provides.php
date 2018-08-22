<?php

return array(
    'listings' => array(
        'hardware' => array('view' => 'hardware_listing', 'i18n' => 'machine.hardware'),
    ),
    'widgets' => array(
      'duplicated_computernames' => array('view' => 'duplicated_computernames_widget', 'icon' => 'fa-bug'),
      'hardware_basemodel' => array('view' => 'hardware_basemodel_widget', 'icon' => 'fa-laptop'),
      'hardware_model' => array('view' => 'hardware_model_widget', 'icon' => 'fa-laptop'),
      'hardware_type' => array('view' => 'hardware_type_widget', 'icon' => 'fa-desktop'),
      'installed_memory' => array('view' => 'installed_memory_widget', 'icon' => 'fa-tasks'),
      'memory' => array('view' => 'memory_widget', 'icon' => 'fa-lightbulb-o'),
      'new_clients' => array('view' => 'new_clients_widget', 'icon' => 'fa-star-o'),
      'os' => array('view' => 'os_widget', 'icon' => 'fa-apple'),
      'osbuild' => array('view' => 'osbuild_widget', 'icon' => 'fa-apple'),
    ),
    'reports' => array(
        'hardware' => array('view' => 'hardware', 'i18n' => 'machine.hardware_report'),
        'backup' => array('view' => 'backup_report', 'i18n' => 'backup.report')
    )
);
