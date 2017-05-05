<?php if ( ! defined( 'KISS' ) ) exit;

	/*
	|===============================================
	| Default settings DON'T CHANGE!
	|===============================================
	|
	| Please don't edit this file, it will get overwritten
	| when a new version gets out. Just add the items you
	| want to change to config.php and change them there.
	|
	*/

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
	$conf['index_page'] = 'index.php?';

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
	$conf['uri_protocol'] = 'AUTO';

	/*
	|===============================================
	| HTTP host
	|===============================================
	|
	| The hostname of the webserver, default automatically
	| determined. no trailing slash
	|
	*/
	$conf['webhost'] = (empty($_SERVER['HTTPS']) ? 'http' : 'https')
		. '://'.$_SERVER[ 'HTTP_HOST' ];

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
	$conf['subdirectory'] = substr(
					    $_SERVER['PHP_SELF'],
					    0,
					    strpos($_SERVER['PHP_SELF'], basename(FC))
				    );

	/*
	|===============================================
	| Sitename
	|===============================================
	|
	| Will appear in the title bar of your browser and as heading on each webpage
	|
	*/
	$conf['sitename'] = 'MunkiReport';

	/*
	|===============================================
	| Hide Non-active Modules
	|===============================================
	|
	| When true, modules that are not in conf['modules'] will not be shown
	|	in the 'Listings' menu.
	*/
	$conf['hide_inactive_modules'] = FALSE;

	/*
        |===============================================
        | Local Admin Threshold Value
        |===============================================
        |
	| This value specifies the minimum number of local admin accounts needed to 
	|	list the computer in the Local Admin Report.  Default is 2.
	*/
	$conf['local_admin_threshold'] = 2;

	/*
	|===============================================
	| Authentication
	|===============================================
	|
	| Currently four authentication methods are supported:
	|
	|	1) Don't require any authentication: paste the following line in your config.php
	|			$conf['auth']['auth_noauth'] = array();
	|
	|	2) (default) Local accounts: visit /index.php?/auth/generate and paste
	|	   the result in your config.php
	|
	|	3) LDAP:
	|		At least fill in these items:
	|		$conf['auth']['auth_ldap']['server']      = 'ldap.server.local'; // One or more servers separated by commas.
	|		$conf['auth']['auth_ldap']['usertree']    = 'uid=%{user},cn=users,dc=server,dc=local'; // Where to find the user accounts.
	|		$conf['auth']['auth_ldap']['grouptree']   = 'cn=groups,dc=server,dc=local'; // Where to find the groups.
	|		$conf['auth']['auth_ldap']['mr_allowed_users'] = array('user1','user2'); // For user based access, fill in users.
	|		$conf['auth']['auth_ldap']['mr_allowed_groups'] = array('group1','group2'); // For group based access, fill in groups.
	|
	|		Optional items:
	|		$conf['auth']['auth_ldap']['userfilter']  = '(&(uid=%{user})(objectClass=posixAccount))'; // LDAP filter to search for user accounts.
	|		$conf['auth']['auth_ldap']['groupfilter'] = '(&(objectClass=posixGroup)(memberUID=%{uid}))'; // LDAP filter to search for groups.
	|		$conf['auth']['auth_ldap']['port']        = 389; // LDAP port.
	|		$conf['auth']['auth_ldap']['version']     = 3; // Use LDAP version 1, 2 or 3.
	|		$conf['auth']['auth_ldap']['starttls']    = FALSE; // Set to TRUE to use TLS.
	|		$conf['auth']['auth_ldap']['referrals']   = FALSE; // Set to TRUE to follow referrals.
	|		$conf['auth']['auth_ldap']['deref']       = LDAP_DEREF_NEVER; // How to dereference aliases. See http://php.net/ldap_search
	|		$conf['auth']['auth_ldap']['binddn']      = ''; // Optional bind DN
	|		$conf['auth']['auth_ldap']['bindpw']      = ''; // Optional bind password
	|		$conf['auth']['auth_ldap']['userscope']   = 'sub'; // Limit search scope to sub, one or base.
	|		$conf['auth']['auth_ldap']['groupscope']  = 'sub'; // Limit search scope to sub, one or base.
	|		$conf['auth']['auth_ldap']['groupkey']    = 'cn'; // The key that is used to determine group membership
	|		$conf['auth']['auth_ldap']['debug']       = 0; // Set to TRUE to debug LDAP.
	|
	|	4) Active Directory: fill the needed and include the lines in your config.php.
	|		 e.g.
	|		$conf['auth']['auth_AD']['account_suffix'] = '@mydomain.local';
	|		$conf['auth']['auth_AD']['base_dn'] = 'DC=mydomain,DC=local'; //set to NULL to auto-detect
	|		$conf['auth']['auth_AD']['domain_controllers'] = array('dc01.mydomain.local'); //can be an array of servers
	|		$conf['auth']['auth_AD']['admin_username'] = NULL; //if needed to perform the search
	|		$conf['auth']['auth_AD']['admin_password'] = NULL; //if needed to perform the search
	|		$conf['auth']['auth_AD']['mr_allowed_users'] = array('macadmin','bossman');
	|		$conf['auth']['auth_AD']['mr_allowed_groups'] = array('AD Group 1','AD Group 2'); //case sensitive
	|
	| Authentication methods are checked in the order that they appear above. Not in the order of your
	| config.php!. You can combine methods 2, 3 and 4
	|
	*/

	/*
	|===============================================
	| reCaptcha Integration
	|===============================================
	|
	| Enable reCaptcha Support on the Authentication Form
	| Request API keys from https://www.google.com/recaptcha
	|
	*/
	$conf['recaptchaloginpublickey'] = '';
	$conf['recaptchaloginprivatekey'] = '';

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
	$conf['authorization']['delete_machine'] = array('admin', 'manager');
	$conf['authorization']['global'] = array('admin');

	/*
	|===============================================
	| Roles
	|===============================================
	|
	| Add users or groups to the appropriate roles array.
	|
	*/
	$conf['roles']['admin'] = array('*');

	/*
	|===============================================
	| Local groups
	|===============================================
	|
	| Create local groups, add users to groups.
	|
	*/
	//$conf['groups']['admin_users'] = array();

	/*
	|===============================================
	| Business Units
	|===============================================
	|
	| Set to TRUE to enable Business Units
	| For more information, see docs/business_units.md
	|
	*/
	$conf['enable_business_units'] = FALSE;

	/*
	|===============================================
	| Force secure connection when authenticating
	|===============================================
	|
	| Set this value to TRUE to force https when logging in.
	| This is useful for sites that serve MR both via http and https
	|
	*/
	$conf['auth_secure'] = FALSE;

	/*
	|===============================================
	| VNC and SSH links, optional links in the client detail view
	|===============================================
	|
	| If you want to have link that opens a screensharing or SSH
	| connection to a client, enable these settings. If you don't
	| want the links, set either to an empty string, eg:
	| $conf['vnc_link'] = "";
	|
	*/
	$conf['vnc_link'] = "vnc://%s:5900";
	$conf['ssh_link'] = "ssh://adminuser@%s";

	/*
	|===============================================
	| Inventory - bundle ignore list
	|===============================================
	|
	| List of bundle-id's to be ignored when processing inventory
	| The list is processed using regex, examples:
	|
	| Skip  all virtual windows apps created by parallels and VMware
	| $conf['bundleid_ignorelist'][] = array('com.parallels.winapp.*', 'com.vmware.proxyApp.*');
	|
	| Skip all Apple apps, except iLife, iWork and Server
	| 'com.apple.(?!iPhoto)(?!iWork)(?!Aperture)(?!iDVD)(?!garageband)(?!iMovieApp)(?!Server).*'
	|
	| Skip all apps with empty bundle-id's
	| '^$'
	|
	*/
	$conf['bundleid_ignorelist'][] = 'com.parallels.winapp.*';
	$conf['bundleid_ignorelist'][] = 'com.vmware.proxyApp.*';

	/*
	|===============================================
	| Inventory - path ignore list
	|===============================================
	|
	| List of bundle-paths to be ignored when processing inventory
	| The list is processed using regex, examples:
	|
	| Skip all apps in /System/Library
	| $conf['bundlepath_ignorelist'][] = '/System/Library/.*';
	|
	| Skip all apps that are contained in an app bundle
	| $conf['bundlepath_ignorelist'][] = '.*\.app\/.*\.app';
	|
	*/
	$conf['bundlepath_ignorelist'] = array('/System/Library/.*');

	/*
	|===============================================
	| GSX lookups
	|===============================================
	|
	| Access to GSX and certificates are required for use of this module
	|
	| The GSX module is designed to be used in place of the warranty module.
	| While both the warranty and GSX modules can be enabled at the same
	| time it is recommended that only one be enabled at a time to prevent
	| the warranty module from overwriting the data provided by the GSX module.
	|
	| Use GSX article OP1474 and
	| https://www.watchmanmonitoring.com/generating-ssl-certificates-to-meet-applecares-august-2015-requirements/
	| to assist with creating certificates and whitelisting your IPs. Addtional documentation can be found in the
	| Readme.md located in the GSX module.
	|
	| To use the GSX module, set enable to TRUE and uncomment and
	| fill out rest of configuration options. When setting the date format
	| make sure it is either 'd/m/y', 'm/d/y', or 'y/m/d'. Lower case letters
	| are required.
	*/
	$conf['gsx_enable'] = FALSE;
	//$conf['gsx_cert'] = '/Library/Keychains/GSX/certbundle.pem';
	//$conf['gsx_cert_keypass'] = '';
	//$conf['gsx_sold_to'] = '1234567890';
	//$conf['gsx_username'] = 'steve@apple.com';
	//$conf['gsx_date_format'] = 'm/d/y';

	/*
	|===============================================
	| DeployStudio
	|===============================================
	|
	| A working DeployStudio server is required for use of this module.
	|
	| To use the DeployStudio module, set 'deploystudio_enable' to TRUE and
	| enter the server, username, and password for accessing your primary
	| DeployStudio server.
	|
	| This module currently only pulls data from the primary DeployStudio
	| server. This means if a machine was imaged off of a replica server
	| its data may not show in MunkiReport.
	*/
	$conf['deploystudio_enable'] = FALSE;
	$conf['deploystudio_server'] = 'https://deploystudio.apple.com:60443'; // no trailing slash
	$conf['deploystudio_username'] = 'deploystudio_user';
	$conf['deploystudio_password'] = 'deploystudio_password';

	/*
	|===============================================
	| USB Devices
	|===============================================
	|
	| By default the USB module will collect information on all USB devices.
	| Setting usb_internal to FALSE will skip all internal devices.
	|
	*/
	$conf['usb_internal'] = TRUE;

	
	/*
	|===============================================
	| Fonts
	|===============================================
	|
	| By default the fonts module will collect information on all fonts.  
	| Setting fonts_system to FALSE will skip all system fonts in /System/Library/Fonts.
	|
	*/
	$conf['fonts_system'] = TRUE;

	/*
	|===============================================
	| Google Maps API Key
	|===============================================
	|
	| To plot the location, you need to use the google maps API. To use the API
	| you should obtain an API key. Without it, you may get blank maps and js
	| errors.
	|
	| Obtain an API Key at the google site:
	| https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true
	| And choose - Create browser API key
	| Add the following line to your config.php file and insert your key.
	| $conf['google_maps_api_key'] = 'YOUR_API_KEY';
	|
	*/
	$conf['google_maps_api_key'] = '';

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
	$conf['curl_cmd'] = array(
		"/usr/bin/curl",
		"--fail",
		"--silent",
		"--show-error");


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
	//$conf['mwa2_link'] = "http://127.0.0.1:8080";

	/*
	|===============================================
	| Modules
	|===============================================
	|
	| List of modules that have to be installed on the client
	| See for possible values the names of the directories
	| in app/modules/
	| e.g. $conf['modules'] = array('disk_report', 'inventory');
	|
	| An empty list installs only the basic reporting modules:
	| Machine and Reportdata
	|
	*/
	$conf['modules'] = array('munkireport', 'managedinstalls');

	/*
	|===============================================
	| Displays module history option
	|===============================================
	|
	| By default this module overrides the information of a client computer
	| on each client's report submission.
	|
	| If you would like to keep displays information until the display is seen again
	| on a different computer use:
	|			$conf['keep_previous_displays'] = TRUE;
	|
	| When not configured, or if set to FALSE, the default behaviour applies.
	*/
	//$conf['keep_previous_displays'] = TRUE;

	/*
	|===============================================
	| Unit of temperature °C or °F
	|===============================================
	|
	| Unit of temperature, possible values: F for Fahrenheit, C for Celsius
	|
	|			$conf['temperature_unit'] = 'F';
	|
	| When not configured, the default behaviour applies.
	| By default temperature units are displayed in Celsius °C.
	|
	*/
	//$conf['temperature_unit'] = 'F';


	/*
	|===============================================
	| Migrations
	|===============================================
	|
	| When a new version of munkireport comes out
	| it might need to update your database structure
	| if you want to allow this, set
	| $conf['allow_migrations'] = TRUE;
	|
	| There is a small overhead (one database query) when setting allow_migrations
	| to TRUE. If you are concerned about performance, you can set allow_migrations
	| to FALSE when you're done migrating.
	|
	*/
	$conf['allow_migrations'] = FALSE;

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
	| $conf['client_passphrases'] = array('secret1', 'secret2');
	|
	|
	*/
	$conf['client_passphrases'] = array();

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
	//$conf['proxy']['server'] = 'proxy.yoursite.org';

	/*
	|===============================================
	| SSL settings
	|===============================================
	|
	| If you need to augment the ssl options to get the machine_model_lookup to
	| work, you can add them here. See also https://secure.php.net/manual/en/context.ssl.php
	|
	| For example php on macOS server 12 cannot lookup the certificate for support.apple.com
	| to fix that, you can override the cafile directive:
	|
	|    $conf['ssl_options'] = array(
	|        'cafile' => '/Library/Frameworks/Python.framework/Versions/3.4/lib/python3.4/site-packages/pip/_vendor/certifi/cacert.pem',
	|    );
	*/
	$conf['ssl_options']  = array();

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
	$conf['request_timeout'] = 5;


	/*
	|===============================================
	| Email Settings
	|===============================================
	|
	| These settings are used for email notifications
	| Only smtp is supported at the moment.
	|
	| 	$conf['email']['use_smtp'] = true;
	| 	$conf['email']['from'] = array('noreply@example.com' => 'Munkireport Mailer');
	|	$conf['email']['smtp_host'] = 'smtp1.example.com;smtp2.example.com';
	|	$conf['email']['smtp_auth'] = true;
	|	$conf['email']['smtp_username'] = 'user@example.com';
	|	$conf['email']['smtp_password'] = 'secret';
	|	$conf['email']['smtp_secure'] = 'tls';
	|	$conf['email']['smtp_port'] = 587;
	|	$conf['email']['locale'] = 'en';
	*/

	/*
	|===============================================
	| Dashboard - IP Ranges
	|===============================================
	|
	| Plot IP ranges by providing an array with labels and
	| a partial IP address. Specify multiple partials in array
	| if you want to group them together.
	| The IP adress part is queried with SQL LIKE
	| Examples:
	| $conf['ip_ranges']['MyOrg'] = '100.99.';
	| $conf['ip_ranges']['AltLocation'] = array('211.88.12.', '211.88.13.');
	|
	*/
    	$conf['ip_ranges'] = array();

 	/*
	|===============================================
	| Dashboard - VLANS
	|===============================================
	|
	| Plot VLANS by providing an array with labels and
	| a partial IP address of the routers. Specify multiple partials in array
	| if you want to group them together.
	| The router IP adress part is queried with SQL LIKE
	| Examples:
	| $conf['ipv4routers']['Wired'] = '211.88.10.1';
	| $conf['ipv4routers']['WiFi'] = array('211.88.12.1', '211.88.13.1');
	| $conf['ipv4routers']['Private range'] = array('10.%', '192.168.%',
	| 	'172.16.%',
	| 	'172.17.%',
	| 	'172.18.%',
	| 	'172.19.%',
	| 	'172.2_.%',
	| 	'172.30.%',
	| 	'172.31.%', );
	| $conf['ipv4routers']['Link-local'] = array('169.254.%');
	|
	*/

	/*
	|===============================================
	| Dashboard - Layout
	|===============================================
	|
	| Dashboard layout is an array of rows that contain
	| an array of widgets. Omit the _widget postfix
	|
	| Up to three small horizontal widgets will show on one line
	|
	| Up to two medium horizontal widgets will show on one line
	|
	| Responsive horizontal widgets will change depending on window size
	|
	| Be aware of medium / dynamic vertical widgets as it may skew the responsive design
	|
	| This is a list of the current dashboard widgets
	|
	| Small horizontal widgets:
        |       bound_to_ds
        |       client (two items)
        |       disk_report
        |       external_displays_count
        |       firmwarepw
        |       gatekeeper
        |       hardware_model
        |       installed memory
        |       localadmin
        |       munki
        |       power_battery_condition
        |       power_battery_health
        |       sip
        |       smart_status
        |       uptime
        |       wifi_state
        |       	|
	| Small horizontal / medium vertical widgets:
	|	network_location
	|
	| Small horizontal / dynamic vertical widgets:
	|	app
	|	duplicated_computernames
	|	filevault
	|	hardware_model
	|	manifests
	|	modified_computernames
	|	munki_versions
	|	new_clients
	|	pending
	|	pending_munki
	|	pending_apple
	|	warranty
	|
	| Medium horizontal widgets:
	|
	| Medium horizontal / dynamic vertical widgets:
	|	hardware_age
	|	hardware_model
	|	memory
	|	os
	|	printer
	|
	| Responsive horizontal widgets:
	|	network_vlan
	|	registered clients
	*/
	$conf['dashboard_layout'] = array(
		array('client', 'messages'),
		array('new_clients', 'pending_apple', 'pending_munki'),
		array('munki', 'disk_report','uptime')
	);

	/*
	|===============================================
	| Apps Version Report
	|===============================================
	|
	| List of applications, by name, that you want to see in the apps
	| version report. If this is not set the report page will appear empty.
	| This is case insensitive but must be an array.
	|
	| Eg:
	| $conf['apps_to_track'] = array('Flash Player', 'Java', 'Firefox', 'Microsoft Excel');
	|
	*/
	$conf['apps_to_track'] = array('Safari');

	/*
	|===============================================
	| Disk Report Widget Thresholds
	|===============================================
	|
	| Thresholds for disk report widget. This array holds two values:
	| free gigabytes below which the level is set to 'danger'
	| free gigabytes below which the level is set as 'warning'
	| If there are more free bytes, the level is set to 'success'
	|
	*/
	$conf['disk_thresholds'] = array('danger' => 5, 'warning' => 10);

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
	$conf['system_path'] = APP_ROOT.'/system/';

	// Path to app folder, with trailing slash
	$conf['application_path'] = APP_ROOT.'/app/';

	// Path to view directory, with trailing slash
	$conf['view_path'] = $conf['application_path'].'views/';

	// Path to controller directory, with trailing slash
	$conf['controller_path'] = $conf['application_path'].'controllers/';

	// Path to modules directory, with trailing slash
	$conf['module_path'] = $conf['application_path'] . "modules/";

	// Routes
	$conf['routes'] = array();
	$conf['routes']['module(/.*)?']	= "module/load$1";

	/*
	|===============================================
	| PDO Datasource
	|===============================================
	|
	| Specify dsn, username, password and options
	| Supported engines: sqlite and mysql
	| Mysql example:
	| 	$conf['pdo_dsn'] = 'mysql:host=localhost;dbname=munkireport';
	| 	$conf['pdo_user'] = 'munki';
	| 	$conf['pdo_pass'] = 'munki';
	| 	$conf['pdo_opts'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
	|
	*/
	$conf['pdo_dsn'] = 'sqlite:'.$conf['application_path'].'db/db.sqlite';
	$conf['pdo_user'] = '';
	$conf['pdo_pass'] = '';
	$conf['pdo_opts'] = array();

	/*
	|===============================================
	| Create table options
	|===============================================
	|
	| For MySQL, define the default table and charset
	|
	*/
	$conf['mysql_create_tbl_opts'] = 'ENGINE=InnoDB DEFAULT CHARSET=utf8';

	/*
	|===============================================
	| Timezone
	|===============================================
	|
	| See http://www.php.net/manual/en/timezones.php for valid values
	|
	*/
	$conf['timezone'] = @date_default_timezone_get();


	/*
	|===============================================
	| Custom folder
	|===============================================
	|
	| Absolute path to folder with custom items.
	| This folder will be searched for widgets, both in views/widgets
	| and in modules/{modulename}/views
	|
	*/
	//$conf['custom_folder'] = APP_ROOT . '/custom/';

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
	//$conf['custom_css'] = '/custom.css';
	//$conf['custom_js'] = '/custom.js';

	/*
	|===============================================
	| Debugging
	|===============================================
	|
	| If set to TRUE, will deliver debugging messages in the page. Set to
	| FALSE in a production environment
	*/
	$conf['debug'] = FALSE;
