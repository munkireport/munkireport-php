<?php

return array(
    'client_tabs' => array(
        'displays-tab' => array(
            'view' => 'displays_info::displays_tab',
            'i18n' => 'displays_info.displays',
            'badge' => 'displays-cnt',
        ),
    ),
    'listings' => array(
        'displays' => array('view' => 'displays_listing', 'i18n' => 'displays_info.displays'),
    ),
    'widgets' => array(
        'external_displays_count' => array('view' => 'external_displays_count_widget'),
    ),
);
