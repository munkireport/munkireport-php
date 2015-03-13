<?php 
/**
 * profile list module class
 *
 * @package munkireport
 * @author
 **/
class Profile_controller extends Module_controller
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
	 * @author
	 **/
	function index()
	{
		echo "You've loaded the profile module!";
	}
	
} // END class default_module
