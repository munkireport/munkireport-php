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
	| Authentication
	|===============================================
	| 
	| Currently three authentication method are supported:
	|
	|	1) Don't require any authentication: paste the following line in your config.php
	|			$conf['auth']['auth_noauth'] = array();
	|
	|	2) (default) Local accounts: visit /index.php?/auth/generate and paste
	|	   the result in your config.php
	|
	|	3) Active Directory: fill the needed and include the lines in your config.php.
	|		 e.g.
	|			$conf['auth']['auth_AD']['account_suffix'] = '@mydomain.local';
	|			$conf['auth']['auth_AD']['base_dn'] = 'DC=mydomain,DC=local'; //set to NULL to auto-detect
	|			$conf['auth']['auth_AD']['domain_controllers'] = array('dc01.mydomain.local'); //can be an array of servers
	|			$conf['auth']['auth_AD']['admin_username'] = NULL; //if needed to perform the search
	|			$conf['auth']['auth_AD']['admin_password'] = NULL; //if needed to perform the search
	|			$conf['auth']['auth_AD']['mr_allowed_users'] = array('macadmin','bossman');
	|			$conf['auth']['auth_AD']['mr_allowed_groups'] = array('AD Group 1','AD Group 2'); //case sensitive
	|
	| They are checked in the order that they appear above. Not in the order of your
	| config.php!. You can combine methods 2 and 3
	|
	*/

	/*
	|===============================================
	| Locale
	|===============================================
	| 
	| You can set the locale string here, this will render certain strings
	| according to locale specific settings
	|
	*/
	$conf['locale'] = 'en_US';

	/*
	|===============================================
	| Language
	|===============================================
	| 
	| You can set the language here, this will change the user interface
	| language. See for possible values the 'lang' directory
	|
	*/
	$conf['lang'] = 'en';

	/*
	|===============================================
	| VNC link, optional link in the client detail view
	|===============================================
	| 
	| If you want to have a link that opens a screensharing connection
	| to a client, enable this setting. If you don't want the link
	| set it to an empty string: $conf['vnc_link'] = "";
	|
	*/
	$conf['vnc_link'] = "vnc://%s:5900";
	
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
	| $conf['bundlepath_ignorelist'][] = '.*\.app\/.*\.app'
	|
	*/
    $conf['bundlepath_ignorelist'] = array('/System/Library/.*');

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
	| If you don't set this item, all available modules are installed (default)
	*/
    //$conf['modules'] = array();

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
	| Check sqlite permissions
	|===============================================
	|
	| Checks if sqlite database is writeable
	| Turn off to speed up sqlite
	|
	*/
	$conf['check_sqlite_perms'] = TRUE;
	
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
	| Debugging
	|===============================================
	|
	| If set to TRUE, will deliver debugging messages in the page. Set to
	| FALSE in a production environment
	*/
	$conf['debug'] = FALSE;
	
	
