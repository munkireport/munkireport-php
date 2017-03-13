<?php

return array(
    'client_tabs' => array(
        'apple-software' => array(
            'view' => 'install_history_tab',
            'view_vars' => array('apple'=> 1),
            'i18n' => 'installhistory.apple_software',
            'badge' => 'history-cnt-1'
          ),
        'third-party-software' => array(
            'view' => 'install_history_tab',
            'view_vars' => array('apple'=> 0),
            'i18n' => 'installhistory.third_party_software',
            'badge' => 'history-cnt-0'
          ),
    ),
);
