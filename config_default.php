<?php if ( ! defined( 'KISS' ) ) exit;
	
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
	$index_page = 'index.php';
	
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
	$uri_protocol = 'AUTO';
	
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
	$webhost = '//'.$_SERVER[ 'HTTP_HOST' ];
	
	
	/*
	|===============================================
	| Subdirectory
	|===============================================
	|  
	| Relative to the webroot, with trailing slash.
	| If you're running munkireport from a subdirectory of a website,
	| enter subdir path here. E.g. if munkireport is accessible here:
	| http://mysite/munkireport/ you should set subdirectory to
	| 'munkireport/'
	| If you're using .htaccess to rewrite urls, you should change that too
	|
	*/
	$subdirectory = '/';
	
	/*
	|===============================================
	| Sitename
	|===============================================
	| 
	| Will appear in the title bar of your browser and as heading on each webpage
	|
	*/
	$GLOBALS['sitename'] = 'MunkiReport';
	
	/*
	|===============================================
	| Inventory - bundle ignore list
	|===============================================
	| 
	| List of bundle-id's to be ignored when processing inventory
	| The list is processed using regex, examples:
	| 
	| Skip  all virtual windows apps created by parallels
	| $GLOBALS['bundleid_ignorelist'][] = 'com.parallels.winapp.*';
	| 
	| Skip all Apple apps, except iLife, iWork and Server
	| 'com.apple.(?!iPhoto)(?!iWork)(?!Aperture)(?!iDVD)(?!garageband)(?!iMovieApp)(?!Server).*'
	|
	*/
	$GLOBALS['bundleid_ignorelist'] = array('com.apple.print.PrinterProxy');
	
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
	$pdo_dsn = 'sqlite:'.$application_folder.'db/db.sqlite';
	$pdo_user = '';
	$pdo_pass = '';
	$pdo_opts = array();
	
	/*
	|===============================================
	| Timezone
	|===============================================
	|
	| See http://www.php.net/manual/en/timezones.php for valid values
	|
	*/
	$timezone = 'Europe/Brussels';
	
	
	/*
	|===============================================
	| Debugging
	|===============================================
	|
	| If set to TRUE, will deliver debugging messages in the page. Set to
	| FALSE in a production environment
	*/
	define('_DEBUG', FALSE );
	
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
	$system_path = APP_ROOT.'/system/'; 

	// Path to app folder, with trailing slash
	$application_folder = APP_ROOT.'/app/';

	// Path to view directory, with trailing slash
	$view_path = $application_folder.'views/';

	// Path to controller directory, with trailing slash
	$controller_path = $application_folder.'controllers/';
	
	// Routes
	$routes = array();
	
