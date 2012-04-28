<?php if ( ! defined( 'KISS' ) ) exit;
	
	//===============================================
	// Default settings.
	//===============================================

	// Path to system folder, with trailing slash
	$system_path = APP_ROOT.'/system/'; 

	// Path to app folder, with trailing slash
	$application_folder = APP_ROOT.'/app/';

	// Path to view directory, with trailing slash
	$view_path = $application_folder.'views/';

	// Path to controller directory, with trailing slash
	$controller_path = $application_folder.'controllers/';

	// Relative to the webroot, with trailing slash
	$subdirectory = '/';
	
	// Index page (leave blank if .htaccess is used, otherwise use index.php)
	// defaults to ''
	//$index_page = '';

	// HTTP host, no trailing slash
	$webhost = 'http://'.$_SERVER[ 'HTTP_HOST' ];
	
	// Uri protocol $_SERVER variable that contains the correct request path, 
	// e.g. 'REQUEST_URI', 'QUERY_STRING', 'PATH_INFO', etc.
	// defaults to AUTO
	//$uri_protocol = 'AUTO';

	// Routes
	$routes = array();

	// PDO Datasource
	// Specify dsn, username, password and options
	// Supported engines: sqlite and mysql
	// Mysql example:
	//$pdo_dsn = 'mysql:host=localhost;dbname=munkireport';
	//$pdo_user = 'munki';
	//$pdo_pass = 'munki';
	//$pdo_opts = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
	$pdo_dsn = 'sqlite:'.$application_folder.'db/db.sqlite';
	$pdo_user = '';
	$pdo_pass = '';
	$pdo_opts = array();

	// Timezone See http://www.php.net/manual/en/timezones.php for valid values
	$timezone = 'Europe/Brussels';
	
	//===============================================
	// Globals
	//===============================================
	$GLOBALS['sitename'] = 'MunkiReport';
	
	define('_DEBUG', FALSE );