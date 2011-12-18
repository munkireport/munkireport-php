<?php
	
	if ( !defined( 'KISS' ) ) exit;
	
	//===============================================
	// Mandatory Settings (please configure)
	//===============================================
	define('WEB_FOLDER','/'); //USED AS RELATIVE WEB-PATH, with trailing slash pls
	define('WEB_HOST', 'http://'.$_SERVER[ 'HTTP_HOST' ] ); //NO SLASH (SO CAN BE CONCATENATED WITH WEB_FOLDER)
	
	define('APP_ROOT', dirname( __FILE__ ).'/' );//WHERE index.php RESIDES
	define('SYS_PATH', dirname( __FILE__ ).'/system/'); //with trailing slash pls
	define('APP_PATH', dirname( __FILE__ ).'/app/' ); //with trailing slash pls
	
	//===============================================
	// Globals
	//===============================================
	$GLOBALS['sitename']='Munkireport';
	$GLOBALS['version'] = '0.7.0';

	//===============================================
	// Other Settings
	//===============================================
	define('VIEW_PATH',APP_PATH.'views/'); //with trailing slash pls
	define('CONTROLLER_PATH',APP_PATH.'controllers/'); //with trailing slash pls
	define('EXT', '.php'); // Default extension
	
	//===============================================
	// Routes
	//===============================================
	$routes = array();
	
	//TODO: Define default controller and default method here
	
	//===============================================
	// Default includes
	//===============================================
	
	
	
	//===============================================
	// Debug
	//===============================================
	define('_DEBUG', FALSE );