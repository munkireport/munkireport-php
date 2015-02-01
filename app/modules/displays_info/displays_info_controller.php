<?php

/**
 * Displays module class
 *
 * @package munkireport
 * @author Noel B.A.
 **/
class Displays_info_controller extends Module_controller
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
		echo "You've loaded the displays module!";
	}


} // END class default_module
