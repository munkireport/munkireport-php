<?php

define( 'KISS', 1 );
define('APP_ROOT', dirname( __FILE__ ).'/' );

// Set default uri protocol override in config.php
$uri_protocol = 'AUTO';

// Index page, override in config.php
$index_page = '';

//===============================================
// Include config
//===============================================
require( 'config.php' );

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
define('WEB_HOST', $webhost); 
define('WEB_FOLDER', $subdirectory);
define('INDEX_PAGE', $index_page);
define('SYS_PATH', $system_path);
define('APP_PATH', $application_folder );
define('VIEW_PATH', $view_path); 
define('CONTROLLER_PATH', $controller_path); 
define('EXT', '.php'); // Default extension

//===============================================
// Debug
//===============================================
ini_set('display_errors', _DEBUG ? 'On' : 'Off' );
error_reporting( _DEBUG ? E_ALL : 0 );

//===============================================
// Includes
//===============================================
require( SYS_PATH.'kissmvc.php' );
require(APP_PATH.'helpers/site_helper'.EXT);

//===============================================
// Session
//===============================================
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
date_default_timezone_set( $timezone );

//set_exception_handler('uncaught_exception_handler');

//===============================================
// Start the controller
//===============================================
$GLOBALS[ 'engine' ] = new Engine( $routes, 'show', 'index', $uri_protocol );

