<?php

return array(
    'listings' => array(
        'hardware' => array('view' => 'hardware_listing', 'i18n' => 'machine.hardware'),
    ),
    'widgets' => array(
      array('view' => 'duplicated_computernames_widget'),
      array('view' => 'hardware_basemodel_widget'),
      array('view' => 'hardware_model_widget'),
      array('view' => 'hardware_type_widget'),
      array('view' => 'installed_memory_widget'),
      array('view' => 'memory_widget'),
      array('view' => 'new_clients_widget'),
      array('view' => 'os_widget'),
    ),
    'reports' => array(
        'hardware' => array('view' => 'hardware', 'i18n' => 'machine.hardware_report'),
        'backup' => array('view' => 'backup_report', 'i18n' => 'backup.report')
    )
);
