<?php
define( 'KISS', 1 );

// Front controller
define('FC', __FILE__ .'/' );

define('APP_ROOT', __DIR__ .'/' );

// Load config
load_conf();

// Load conf (keeps variables out of global space)
function load_conf()
{
	// Load default configuration
	require_once(APP_ROOT . "config_default.php");

	if ((include_once APP_ROOT . "config.php") !== 1)
	{
		fatal(APP_ROOT. "config.php is missing!<br>
	Unfortunately, Munkireport does not work without it</p>");
	}

	// Convert auth_config to config item
	if(isset($auth_config))
	{
		$conf['auth']['auth_config'] = $auth_config;
	}

	$GLOBALS['conf'] =& $conf;
}


/**
 * Get config item
 * @param string config item
 * @param string default value (optional)
 * @author AvB
 **/
function conf($cf_item, $default = '')
{
	return array_key_exists($cf_item, $GLOBALS['conf']) ? $GLOBALS['conf'][$cf_item] : $default;
}

/*
	A simple debug logger that mutes output when debug is FALSE.
 */
function debug($msg)
{
	if (conf('debug'))
	{
		printf('<span class="debug">[DEBUG] %s </span>', 
			is_string($msg) ? $msg : var_export($msg, TRUE));
	}
}

/**
 * Fatal error, show message and die
 *
 * @author AvB
 **/
function fatal($msg)
{
	include('assets/html/fatal_error.html');
	exit(1);
}


//===============================================
// Defines
//===============================================
define('INDEX_PAGE', conf('index_page'));
define('SYS_PATH', conf('system_path') );
define('APP_PATH', conf('application_path') );
define('VIEW_PATH', conf('view_path')); 
define('MODULE_PATH', conf('module_path')); 
define('CONTROLLER_PATH', conf('controller_path')); 
define('EXT', '.php'); // Default extension

//===============================================
// Debug
//===============================================
ini_set('display_errors', conf('debug') ? 'On' : 'Off' );
error_reporting( conf('debug') ? E_ALL : 0 );

//===============================================
// Includes
//===============================================
require( SYS_PATH.'kissmvc.php' );
require( APP_PATH.'helpers/site_helper'.EXT );

//===============================================
// Session
//===============================================
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_path', conf('subdirectory'));
session_start();
date_default_timezone_set( conf('timezone') );
setlocale(LC_ALL, conf('locale'));


//set_exception_handler('uncaught_exception_handler');

//===============================================
// Quick permissions check for sqlite operations
//===============================================
if (conf('check_sqlite_perms') && strpos( conf('pdo_dsn'), "sqlite") === 0)
{
	$dbh = getdbh();
	
	if( $dbh->exec( 'CREATE TABLE `tmp` (id)' ) === FALSE )
	{
		if($dbh->exec( 'DROP TABLE `tmp`' ) === FALSE )
		{
			$err = $dbh->errorInfo();
			fatal('sqlite: '.$err[2]);
		}
	}
	$dbh->exec( 'DROP TABLE `tmp`' );
}

//===============================================
// Start the controller
//===============================================
$uri_protocol = conf('uriProtocol');
new Engine($conf['routes'],'show','index',$conf['uri_protocol']);