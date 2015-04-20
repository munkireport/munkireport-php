<?php 
/**
 * Timemachine module class
 *
 * @package munkireport
 * @author AvB
 **/
class Timemachine_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
	}
	/**
	 * Default method
	 *
	 * @author
	 **/
	function index()
	{
		echo "You've loaded the timemachine module!";
	}


	
} // END class Timemachine_controller
