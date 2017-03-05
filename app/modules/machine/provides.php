<?php

return array(
    'listings' => array(
        array('view' => 'hardware_listing'),
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
        'hardware' => array('view' => 'hardware'),
        'backup' => array('view' => 'backup_report')
    )
);
