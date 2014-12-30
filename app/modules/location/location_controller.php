<?php 

/**
 * location status module class
 *
 * @package munkireport
 * @author
 **/
class Location_controller extends Module_controller
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
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the location module!";
	}

	
} // END class default_module