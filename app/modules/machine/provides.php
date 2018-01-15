<?php

return array(
    'listings' => array(
        'hardware' => array('view' => 'hardware_listing', 'i18n' => 'machine.hardware'),
    ),
    'widgets' => array(
      'duplicated_computernames' => array('view' => 'machine::duplicated_computernames_widget'),
      'hardware_basemodel' => array('view' => 'machine::hardware_basemodel_widget'),
      'hardware_model' => array('view' => 'machine::hardware_model_widget'),
      'hardware_type' => array('view' => 'machine::hardware_type_widget'),
      'installed_memory' => array('view' => 'machine::installed_memory_widget'),
      'memory' => array('view' => 'machine::memory_widget'),
      'new_clients' => array('view' => 'machine::new_clients_widget'),
      'os' => array('view' => 'machine::os_widget'),
    ),
    'reports' => array(
        'hardware' => array('view' => 'hardware', 'i18n' => 'machine.hardware_report'),
        'backup' => array('view' => 'backup_report', 'i18n' => 'backup.report')
    )
);
