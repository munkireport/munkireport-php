<?php

/**
 * backup2go class
 *
 * @package munkireport
 * @author
 **/
class backup2go_controller extends Module_controller
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
		echo "You've loaded the backup2go module!";
	}


	/**
	 * Get  stats
	 *
	 * @return void
	 * @author 
	 **/
	function get_stats($hours = 24)
	{
		$obj = new View();

		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => 'Not authorized'));
			return;
		}

		$model = new Backup2go_Model;
		$obj->view('json', array('msg' => $model->get_stats($hours)));
	}

} // END class default_module
