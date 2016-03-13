<?php 

/**
 * Notification module class
 *
 * @package munkireport
 * @author 
 **/
class Notification_controller extends Module_controller
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
		echo "You've loaded the notification module!";
	}

	/**
	 * REST interface, returns json with notification objects
	 **/
	function get_list()
	{
		
        $obj = new View();
		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authorized')));
		}
		else
		{
			$notify_obj = new Notification_model();
			$obj->view('json', array('msg' => $notify_obj->get_list()));
		}

	}

	
} // END class notification_module