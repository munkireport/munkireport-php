<?php
define( 'KISS', 1 );

// Front controller
define('FC', __FILE__ .'/' );

define('PUBLIC_ROOT', dirname(__FILE__) . '/' );
define('APP_ROOT', dirname(PUBLIC_ROOT) . '/' );

// Pass on https forward to $_SERVER['HTTPS']
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
{
	$_SERVER['HTTPS'] = 'on';
}

// Load config
load_conf();

// Load conf (keeps variables out of global space)
function load_conf()
{
	$conf = array();

	$GLOBALS['conf'] =& $conf;

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

/**
 * Get session item
 * @param string session item
 * @param string default value (optional)
 * @author AvB
 **/
function sess_get($sess_item, $default = '')
{
	if (! isset($_SESSION))
	{
		return $default;
	}

	return array_key_exists($sess_item, $_SESSION) ? $_SESSION[$sess_item] : $default;
}

/**
 * Set session item
 * @param string session item
 * @param string value
 * @author AvB
 **/
function sess_set($sess_item, $value)
{
	if (! isset($_SESSION))
	{
		return false;
	}

	$_SESSION[$sess_item] = $value;

	return true;
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
// Autoloading
//===============================================
spl_autoload_register('munkireport_autoload');
if ((include APP_ROOT . "vendor/autoload.php") === false)
{
	fatal("vendor/autoload.php is missing!<br>
Please run `composer install` in the munkireport directory</p>");
}


//===============================================
// Timezone
//===============================================
date_default_timezone_set( conf('timezone') );

//set_exception_handler('uncaught_exception_handler');


//===============================================
// Start the controller
//===============================================
$uri_protocol = conf('uriProtocol');
new Engine($conf['routes'],'show','index',$conf['uri_protocol']);
