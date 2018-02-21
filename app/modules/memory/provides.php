<?php

return array(
    'client_tabs' => array(
        'memory-tab' => array('view' => 'memory_tab', 'i18n' => 'memory.clienttab', 'badge' => 'memory-cnt'),
        'ram-tab' => array('view' => 'ram_tab', 'i18n' => 'memory.clienttab_ram', 'badge' => 'ram-cnt'),
    ),
    'widgets' => array(
      'upgradable_memory' => array('view' => 'upgradable_memory_widget'),
      'memory_pressure' => array('view' => 'memory_pressure_widget'),
    ),
    'listings' => array(
        'memory' => array('view' => 'memory_listing', 'i18n' => 'memory.clienttab'),
        'ram' => array('view' => 'ram_listing', 'i18n' => 'memory.clienttab_ram'),
    ),
);
