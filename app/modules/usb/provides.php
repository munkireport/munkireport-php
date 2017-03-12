<?php

return array(
    'client_tabs' => array(
        'usb-tab' => array('view' => 'usb_tab', 'i18n' => 'usb.devices', 'badge' => 'usb-cnt'),
    ),
    'listings' => array(
        'usb' => array('view' => 'usb_listing', 'i18n' => 'usb.devices'),
    ),
    'widgets' => array(
        array('view' => 'usb_devices_widget'),
        array('view' => 'usb_types_widget'),
    ),
    'reports' => array(
        'peripheral' => array('view' => 'usb_report', 'i18n' => 'usb_report'),
    ),
);
