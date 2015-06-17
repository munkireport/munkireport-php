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

	/**
	 * Get statistics
	 *
	 * @return void
	 * @author 
	 **/
	function get_stats($mount_point = '/')
	{
        $obj = new View();

        if( ! $this->authorized())
        {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $disk_report = new Disk_report_model;
        $obj->view('json', array('msg' => $disk_report->get_stats($mount_point)));

	}

} // END class disk_report_module