<?php 

/**
 * Disk report controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Disk_report_controller extends Module_controller
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
		echo "You've loaded the disk report module!";
	}


	function get_errors($listname = '')
	{
		$diskreport = new Disk_report_model();
		$errors = count($diskreport->retrieve_many('percentage > 80'));
		$data = array('diskfull' => $errors);
		$obj = new View();
		$obj->view('json', array('msg' => $data));
	}
} // END class disk_report_module