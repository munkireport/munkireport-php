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
    include(APP_ROOT . 'public/assets/html/fatal_error.html');
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
initConfig();
configAppendFile(APP_ROOT . 'app/config/app.php');
configAppendFile(APP_ROOT . 'app/config/db.php', 'connection');
configAppendFile(APP_ROOT . 'app/config/dashboard.php', 'dashboard');
configAppendFile(APP_ROOT . 'app/config/widget.php', 'widget');
configAppendFile(APP_ROOT . 'app/config/auth.php');
loadAuthConfig();
// echo '<pre>';print_r($GLOBALS['conf']);exit;


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
// Debug
//===============================================
ini_set('display_errors', conf('debug') ? 'On' : 'Off' );
error_reporting( conf('debug') ? E_ALL : 0 );

//===============================================
// Includes
//===============================================
require( APP_ROOT.'/system/kissmvc.php' );
require( APP_ROOT.'/app/helpers/site_helper.php' );

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
