<?php

/**
 * The purpose of this configuration file is to capture config items that are outside the definition of what Laravel
 * provides.
 */
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
    | Unit of temperature °C or °F
    |===============================================
    |
    | Unit of temperature, possible values: F for Fahrenheit, C for Celsius
    |
    |            $conf['temperature_unit'] = 'F';
    |
    | When not configured, the default behaviour applies.
    | By default temperature units are displayed in Celsius °C.
    |
    */
    'temperature_unit' => env('TEMPERATURE_UNIT', 'C'),

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
    | Default Theme
    |===============================================
    |
    | Sets the default theme for new logins/users.
    |
    */
    'default_theme' => env('DEFAULT_THEME', 'Default'),

    /*
    |===============================================
    | Roles
    |===============================================
    |
    | Add users or groups to the appropriate roles array.
    |
    */
    'roles' => [
        'admin' => mr_env('ROLES_ADMIN', ['*']),
        'manager' => mr_env('ROLES_MANAGER', []),
        'archiver' => mr_env('ROLES_ARCHIVER', []),
    ],

    /*
    |===============================================
    | Local groups
    |===============================================
    |
    | Create local groups, add users to groups.
    |
    */
    'groups' => [
        'admin_users' => mr_env('GROUPS_ADMIN_USERS', [])
    ],

    /*
    |===============================================
    | Business Units
    |===============================================
    |
    | Set to TRUE to enable Business Units
    | For more information, see docs/business_units.md
    |
    */
    'enable_business_units' => mr_env('ENABLE_BUSINESS_UNITS', false),

    /*
    |===============================================
    | VNC and SSH links, optional links in the client detail view
    |===============================================
    |
    | Substitutions key:
    |   %s = remote IP
    |   %remote_ip = remote IP (same as above but easier to read in the config)
        |   %u = logged in username
        |   %network_ip_v4 = local network ipv4 address
        |   %network_ip_v6 = local network ipv6 address
    |
    | If you want to have link that opens a screensharing or SSH
    | connection to a client, enable these settings. If you don't
    | want the links, set either to an empty string, eg:
    | $conf['vnc_link'] = "";
    |
    | If you want to authenticate with SSH using the currently logged in user
    | replace the username in the SSH config with %u:
    | $conf['ssh_link'] = "ssh://%u@%s";
    */
    'vnc_link' => env('VNC_LINK', "vnc://%s:5900"),
    'ssh_link' => env('SSH_LINK', "ssh://adminuser@%s"),


    /*
    |===============================================
    | Curl
    |===============================================
    |
    | Define path to the curl binary and add options
    | this is used by the installer script.
    | Override to use custom path and add or remove options, some environments
    | may need to add "--insecure" if the servercertificate is not to be
    | checked.
    |
    */
    'curl_cmd' => mr_env('CURL_CMD',  [
        "/usr/bin/curl",
        "--fail",
        "--silent",
        "--show-error"
    ]),


    /*
    |===============================================
    | MunkiWebAdmin2
    |===============================================
    |
    | MunkiWebAdmin2 (MWA2) is a web-based administration tool for Munki
    | that focuses on editing manifests and pkginfo files.
    |
    | To learn more about MWA2 visit: https://github.com/munki/mwa2
    |
    */
    'mwa2_link' => mr_env('MWA2_LINK'),

    /*
    |===============================================
    | Modules
    |===============================================
    |
    | List of modules that have to be installed on the client
    | See for possible values the names of the directories
    | in app/modules/
    | e.g. $conf['modules'] = ['disk_report', 'inventory'];
    |
    | An empty list installs only the basic reporting modules:
    | Machine and Reportdata
    |
    */
    'modules' => mr_env('MODULES', ['munkireport', 'managedinstalls', 'disk_report']),

    /*
    |===============================================
    | Custom css and js
    |===============================================
    |
    | If you want to override the default css or default js
    | you can specify a custom file that will be included
    | in the header (css) and footer (js)
    |
    */
    'custom_css' => env('CUSTOM_CSS', ''),
    'custom_js' => env('CUSTOM_JS', ''),

    /*
    |===============================================
    | Show help
    |===============================================
    |
    | Add a help button to the navigation bar, defaults to true
    |
    */
    'show_help' => mr_env('SHOW_HELP', true),

    /*
    |===============================================
    | Custom Help URL
    |===============================================
    |
    | If you want to override the default help url
    | (MunkiReport's GitHub Wiki), you can specify which URL
    | to redirect to (in a new tab).
    |
    */
    'help_url' => env('HELP_URL', 'https://github.com/munkireport/munkireport-php/wiki'),

    /*
    |===============================================
    | Client detail layout
    |===============================================
    |
    | If you want to override the default help url
    | (MunkiReport's GitHub Wiki), you can specify which URL
    | to redirect to (in a new tab).
    |
    */
    'detail_widget_list' => mr_env('CLIENT_DETAIL_WIDGETS', [
        'machine_info_1',
        'machine_info_2',
        'comment_detail',
        'hardware_detail',
        'software_detail',
        'storage_detail',
        '*'
    ]),


    /*
    |===============================================
    | Client passphrases
    |===============================================
    |
    | List of passphrases that the client can use to authenticate
    |
    | On the client:
    | defaults write /Library/Preferences/MunkiReport Passphrase 'secret1'
    |
    | On the server:
    | $conf['client_passphrases'] = ['secret1', 'secret2'];
    |
    |
    */
    'client_passphrases' => mr_env('CLIENT_PASSPHRASES', []),

    /*
    |===============================================
    | Client scriptnames
    |===============================================
    |
    | Override these if you want to provide your own custom scripts that
    | call the munkireport scripts
    */
    'preflight_script' => env('PREFLIGHT_SCRIPT', 'preflight'),
    'postflight_script' => env('POSTFLIGHT_SCRIPT', 'postflight'),
    'report_broken_client_script' => env('REPORT_BROKEN_CLIENT_SCRIPT', 'report_broken_client'),

    /*
    |===============================================
    | Proxy settings
    |===============================================
    |
    | If you are behind a proxy, MunkiReport may be unable to
    | retrieve warranty and model information from Apple.
    |
    | Note that there is only authenticated proxy support for
    | basic authentication
    |
    | $conf['proxy']['server'] = 'proxy.yoursite.org'; // Required
    | $conf['proxy']['username'] = 'proxyuser'; // Optional
    | $conf['proxy']['password'] = 'proxypassword'; Optional
    | $conf['proxy']['port'] = 8080; // Optional, defaults to 8080
    |
    */
    'proxy' => [
        'server' => env('PROXY_SERVER', ''),
        'username' => env('PROXY_USERNAME', ''),
        'password' => env('PROXY_PASSWORD', ''),
        'port' => env('PROXY_PORT', 0),
    ],

    /*
    |===============================================
    | Guzzle settings
    |===============================================
    |
    | Guzzle is used to make http connections to other servers (e.g. apple.com)
    |
    | Guzzle will choose the appropriate handler based on your php installation
    | You can override this behaviour by specifying the handler here.
    |
    | Valid options are 'curl', 'stream' or 'auto' (default)
    | For CA Bundle options see http://docs.guzzlephp.org/en/stable/request-options.html#verify
    */
    'guzzle_handler' => env('GUZZLE_HANDLER', 'auto'),

    /*
    |===============================================
    | Request timeout
    |===============================================
    |
    | Timeout for retrieving warranty and model information from Apple.
    |
    | Timeout in seconds
    |
    */
    'request_timeout' => mr_env('REQUEST_TIMEOUT', 5),

    /*
    |===============================================
    | Apple Hardware Icon Url
    |===============================================
    |
    | URL to retrieve icon from Apple
    |
    */
    'apple_hardware_icon_url' => env('APPLE_HARDWARE_ICON_URL', 'https://km.support.apple.com/kb/securedImage.jsp?configcode=%s&amp;size=240x240'),

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
    'view_path' => resource_path('views/'),

    // Path to controller directory, with trailing slash
    'controller_path' => app_path('controllers/'),

    // Path to modules directory, with trailing slash
    'module_path' => APP_ROOT . "/vendor/munkireport/",

    // Path to storage directory
    'storage_path' => env('STORAGE_PATH', APP_ROOT . "/storage/"),

    // Routes
    'routes' => [
        'module(/.*)?' => "module/load$1",
    ],

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
        'APP_URL',
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
    | Notifications
    |===============================================
    |
    | This setting allows you to forward any of the events which
    | traditionally go to the `event` table in the database (which turn up on the event widget),
    | to one or more Notification channels, such as E-mail, Slack, Teams etc.
    |
    | Webhook URL's / Secrets are configured in services.php
    */
    'notifications' => [
        'forward_events' => false,
//        'slack' => [
//            'from' => env('SLACK_FROM', 'MunkiReport'),
//            'to' => env('SLACK_TO', ''),
//        ]
    ],

    /*
    |===============================================
    | Alpha Features
    |===============================================
    |
    | These feature flags allow you to test individual alpha
    | features but are turned off by default to protect you from instability.
    |
    | They aren't even guaranteed to work!
    */
    'alpha_features' => [
        // Enable Business Units rewrite
        'business_units_v2' => env('ALPHA_FEATURE_BUSINESS_UNITS_V2', False),
        // Enable Flexible Query (GraphQL)
        'flexible_query' => env('ALPHA_FEATURE_FLEXIBLE_QUERY', false),
    ]
];
