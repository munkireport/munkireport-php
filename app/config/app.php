<?php

return [


    /*
    |===============================================
    | Local directory
    |===============================================
    |
    | Path to the local directory where settings, users and certificates are stored
    |
    */
    'local' => env('LOCAL_DIRECTORY_PATH', APP_ROOT . 'local/'),

    /*
    |===============================================
    | HTTP host
    |===============================================
    |
    | The hostname of the webserver, default automatically
    | determined. no trailing slash
    |
    */
    'webhost' => mr_env(
        'WEBHOST',
        function(){
            if(PHP_SAPI == 'cli') {
              return '';
            }
            return (SslRequest() ? 'https' : 'http') . '://'.$_SERVER[ 'HTTP_HOST' ];
        },
        'string'
    ),

    /*
    |===============================================
    | Index page
    |===============================================
    |
    | Default is index.php? which is the most compatible form.
    | You can leave it blank if you want nicer looking urls.
    | You will need a server which honors .htaccess (apache) or
    | figure out how to rewrite urls in the server of your choice.
    |
    */
    'index_page' => env('INDEX_PAGE', 'index.php?'),

    /*
    |===============================================
    | Uri protocol
    |===============================================
    |
    | $_SERVER variable that contains the correct request path,
    | e.g. 'REQUEST_URI', 'QUERY_STRING', 'PATH_INFO', etc.
    | defaults to AUTO
    |
    */
    'uri_protocol' => env('URI_PROTOCOL', 'AUTO'),

    /*
    |===============================================
    | Subdirectory
    |===============================================
    |
    | Relative to the webroot, with trailing slash.
    | If you're running munkireport from a subdirectory of a website,
    | enter subdir path here. E.g. if munkireport is accessible here:
    | http://mysite/munkireport/ you should set subdirectory to
    | '/munkireport/'
    | If you're using .htaccess to rewrite urls, you should change that too
    | The code below is for automagically deterimining your subdirectory,
    | if it fails, just add $conf['subdirectory'] = '/your_sub_dir/' in
    | config.php
    |
    */
    'subdirectory' => mr_env(
        'SUBDIRECTORY',
        function (){
            return substr(
                $_SERVER['PHP_SELF'],
                0,
                strpos($_SERVER['PHP_SELF'], 'index.php')
            );
        },
        'string'
    ),

    /*
    |===============================================
    | Hide Non-active Modules
    |===============================================
    |
    | When false, all modules will be shown in the interface like
    |    in the 'Listings' menu.
    */
    'hide_inactive_modules' => mr_env('HIDE_INACTIVE_MODULES', true),

    /*
    |===============================================
    | Module Search Paths
    |===============================================
    |
    | Filesystem paths to search for modules
    |    replaces the implicit 'custom' module path
    */
    'module_search_paths' => mr_env('MODULE_SEARCH_PATHS', []),

    /*
    |===============================================
    | Role Based Authorization
    |===============================================
    |
    | Authorize actions by listing roles appropriate array.
    | Don't change these unless you know what you're doing, these roles are
    | also used by the Business Units
    |
    */
    'authorization' => [
        'archive' => mr_env('AUTHORIZATION_ARCHIVE', ['admin', 'manager', 'archiver']),
        'delete_machine' => mr_env('AUTHORIZATION_DELETE_MACHINE', ['admin', 'manager']),
        'global' => mr_env('AUTHORIZATION_GLOBAL', ['admin']),
    ],


    /*
    |===============================================
    | App settings
    |===============================================
    |
    | If the webapp is in a different directory as index.php, adjust
    | the variables below. For enhanced security it is advised to put the
    | webapp in a directory that is not visible to the internet.
    */
    // Path to system folder, with trailing slash
    'system_path' => APP_ROOT.'/system/',

    // Path to app folder, with trailing slash
//    'application_path' => APP_ROOT.'/app/',

    // Path to view directory, with trailing slash
    'view_path' => APP_ROOT.'/app/views/',

    // Path to controller directory, with trailing slash
    'controller_path' => APP_ROOT.'/app/controllers/',

    // Path to modules directory, with trailing slash
    'module_path' => APP_ROOT . "/vendor/munkireport/",

    // Path to storage directory
    'storage_path' => env('STORAGE_PATH', APP_ROOT . "/storage/"),

    // Routes
    'routes' => [
        'module(/.*)?' => "module/load$1",
    ],

];
