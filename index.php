<?php
define( 'KISS', 1 );

// Front controller
define('FC', __FILE__ .'/' );

define('APP_ROOT', __DIR__ .'/' );

//===============================================
// Include config
//===============================================
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


// Make config part of global array
$GLOBALS['conf'] =& $conf;

// Config getter
function conf($cf_item)
{
	return array_key_exists($cf_item, $GLOBALS['conf']) ? $GLOBALS['conf'][$cf_item] : '';
}

/*
	A simple debug logger that mutes output when debug is FALSE.
 */
function debug($message)
{
	if (conf('debug'))
	{
		echo "<span class='debug'>[DEBUG] "
			. is_string($message) ? $message : var_export($message, TRUE)
			. "</span>";
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
if (strpos( conf('pdo_dsn'), "sqlite") === 0) {
	$dsnParts = explode(":", conf('pdo_dsn'));
	$dbPath = $dsnParts[1];
	$dbDir = dirname($dbPath);
	$errors = FALSE;
	if (!is_writable($dbDir)) {
		echo "Database directory isn't writable by the webserver";
		debug(" - " . $dbDir);
		echo "<br />";
		$errors = TRUE;
	}
	if (file_exists($dbPath) && !is_writable($dbPath)) {
		echo "Database isn't writable by the webserver";
		debug(" - " . $dbPath);
		$errors = TRUE;
	}
	if ($errors == TRUE)
		exit;
}

//===============================================
// Start the controller
//===============================================
$uri_protocol = conf('uriProtocol');
new Engine($conf['routes'],'show','index',$conf['uri_protocol']);