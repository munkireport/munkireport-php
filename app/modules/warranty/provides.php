<?php

return array(
    'listings' => array(
        'warranty' => array('view' => 'warranty_listing', 'i18n' => 'warranty.warranty'),
    ),
    'widgets' => array(
        'hardware_age' => array('view' => 'warranty::hardware_age_widget'),
        'hardware_warranty' => array('view' => 'warranty::hardware_warranty_widget'),
        'warranty' => array('view' => 'warranty::warranty_widget'),
    ),
);
