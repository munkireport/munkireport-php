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
	| Index page. Leave blank if .htaccess or another server rewrite method
	| is used. On some servers a question mark has to be added:
	| $index_page = 'index.php?';
	|
	*/
	$conf['index_page'] = 'index.php';
	
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
	| Syntax below with protocol relative url
	| http://paulirish.com/2010/the-protocol-relative-url/
	| 
	*/
	$conf['webhost'] = '//'.$_SERVER[ 'HTTP_HOST' ];
	
	
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
	| Skip  all virtual windows apps created by parallels
	| $conf['bundleid_ignorelist'][] = 'com.parallels.winapp.*';
	| 
	| Skip all Apple apps, except iLife, iWork and Server
	| 'com.apple.(?!iPhoto)(?!iWork)(?!Aperture)(?!iDVD)(?!garageband)(?!iMovieApp)(?!Server).*'
	|
	*/
	$conf['bundleid_ignorelist'][] = 'com.parallels.winapp.*';

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
	| pdo_dsn = 'mysql:host=localhost;dbname=munkireport';
	| pdo_user = 'munki';
	| pdo_pass = 'munki';
	| pdo_opts = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
	|
	*/
	$conf['pdo_dsn'] = 'sqlite:'.$conf['application_path'].'db/db.sqlite';
	$conf['pdo_user'] = '';
	$conf['pdo_pass'] = '';
	$conf['pdo_opts'] = array();
	
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
	
	
