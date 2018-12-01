<?php

// Pass on https forward to $_SERVER['HTTPS']
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
{
	$_SERVER['HTTPS'] = 'on';
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
// Autoloading
//===============================================
require_once APP_ROOT.'app/helpers/env_helper.php';
if ((include APP_ROOT . "vendor/autoload.php") === false)
{
    fatal("vendor/autoload.php is missing!<br>
Please run `composer install` in the munkireport directory</p>");
}

// Include config helper
require APP_ROOT.'app/helpers/config_helper.php';

// Load config
initDotEnv();
init_conf();
configAppendFile(APP_ROOT . 'config/app.php');
configAppendFile(APP_ROOT . 'config/db.php', 'connection');
configAppendFile(APP_ROOT . 'config/dashboard.php', 'dashboard_layout');
// echo '<pre>';print_r($GLOBALS['conf']);exit;

function init_conf()
{
    $GLOBALS['conf'] = [];
}

// Load conf (keeps variables out of global space)
function load_conf()
{
	$conf = array();

	$GLOBALS['conf'] =& $conf;

	// Convert auth_config to config item
	if(isset($auth_config))
	{
		$conf['auth']['auth_config'] = $auth_config;
	}

}

/**
 * Add config array to global config array
 *
 *
 * @param array $configArray
 */
function configAppendArray($configArray, $namespace = '')
{
	if($namespace){
    $GLOBALS['conf'] += [$namespace => $configArray];
  }
  else{
    $GLOBALS['conf'] += $configArray;
  }
}

/**
 * Add config file to global config array
 *
 *
 * @param array $configPath
 */
function configAppendFile($configPath, $namespace = '')
{
	$config = require $configPath;
	configAppendArray($config, $namespace);
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

//===============================================
// Defines
//===============================================
define('INDEX_PAGE', conf('index_page'));
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
require( APP_ROOT.'/system/kissmvc.php' );
require( APP_ROOT.'/app/helpers/site_helper'.EXT );

spl_autoload_register('munkireport_autoload');

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
