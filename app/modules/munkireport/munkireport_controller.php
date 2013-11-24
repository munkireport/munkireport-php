<?php 

/**
 * Munkireport module class
 *
 * @package munkireport
 * @author 
 **/
class Munkireport_controller extends Module_controller
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
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the munkireport module!";
	}

	/**
	 * Show detail information
	 *
	 * @author AvB
	 **/
	function pending()
	{
		if( ! isset($_SESSION['user']))
		{
			redirect('auth/login');
		}
		
		$data['page'] = '';
		$obj = new View();
		$obj->view('pending', $data, $this->view_path);
	}

	
} // END class default_module