<?php

return array(
    'client_tabs' => array(
        'usb-tab' => array('view' => 'usb_tab', 'i18n' => 'nav.listings.usb', 'badge' => 'usb-cnt'),
    ),
    'listings' => array(
        array('view' => 'usb_listing'),
    ),
    'widgets' => array(
        array('view' => 'usb_devices_widget'),
        array('view' => 'usb_types_widget'),
    ),
);
