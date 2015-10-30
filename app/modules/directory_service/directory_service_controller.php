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
	
	/**
	 * Get bound Statistics
	 *
	 *
	 **/
	public function get_bound_stats()
	{
		$obj = new View();

		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authenticated')));
			return;
		}
		
		$dm = new Directory_service_model();
		$obj->view('json', array('msg' => $dm->get_bound_stats()));
		
	}

	
} // END class default_module

