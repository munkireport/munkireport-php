<?php 

/**
 * Directory Service status module class
 *
 * @package munkireport
 * @author 
 **/
class Directory_service_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 *
	 * @author gmarnin
	 **/
	function index()
	{
		echo "You've loaded the directoryservice module!";
	}

	
} // END class default_module

