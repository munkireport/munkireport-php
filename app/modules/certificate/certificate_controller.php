<?php 

/**
 * Certificate_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Certificate_controller extends Module_controller
{
	function __construct()
	{
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the certificate report module!";
	}

} // END class Certificate_controller