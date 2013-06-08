<?php
define( 'KISS', 1 );
define('APP_ROOT', __DIR__ .'/' );


//===============================================
// Include config
//===============================================
require_once(APP_ROOT . "app/models/Config.php");


/*
	A simple debug logger that mutes output when debugModeEnabled is FALSE.
 */
function debug($message)
{
	if (Config::get('debugModeEnabled'))
	{
		echo "<span class='debug'>[DEBUG] "
			. is_string($message) ? $message : var_export($message, TRUE)
			. "</span>";
	}
}


// Set default uri protocol override in config.php
$uri_protocol = 'AUTO';

// Index page, override in config.php
$index_page = '';


//===============================================
// Database
//===============================================
$GLOBALS['db'] = array(
	'dsn' => $pdo_dsn, 
	'user' => isset($pdo_user) ? $pdo_user : '',
	'pass' => isset($pdo_pass) ? $pdo_pass : '',
	'opts' => isset($pdo_opts) ? $pdo_opts : array()
	);

//===============================================
// Defines
//===============================================
define('WEB_HOST', Config::get('webHost')); 
define('WEB_FOLDER', Config::get('subdirectory'));
define('INDEX_PAGE', Config::get('indexPage'));
define('SYS_PATH', Config::get('paths.system') );
define('APP_PATH', Config::get('paths.application') );
define('VIEW_PATH', Config::get('paths.view')); 
define('CONTROLLER_PATH', Config::get('paths.controller')); 
define('EXT', '.php'); // Default extension

//===============================================
// Debug
//===============================================
ini_set('display_errors', Config::get('debugModeEnabled') ? 'On' : 'Off' );
error_reporting( Config::get('debugModeEnabled') ? E_ALL : 0 );

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
session_start();
date_default_timezone_set( Config::get('timezone') );

//set_exception_handler('uncaught_exception_handler');

//===============================================
// Quick permissions check for sqlite operations
//===============================================
if (strpos( Config::get('pdo.dsn'), "sqlite") === 0) {
	$dsnParts = explode(":", Config::get('pdo.dsn'));
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
$GLOBALS[ 'engine' ] = new Engine(
	Config::get('routes'),
	'show',
	'index',
	Config::get('uriProtocol')
);