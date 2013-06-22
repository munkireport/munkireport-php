<?php 

/**
 * Disk report controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Disk_report_controller
{
	function get_errors($listname = '')
	{
		$data = array('slap', 'joop');
		$obj = new View();
		$obj->view('json', array('msg' => $data));
	}
} // END class disk_report_module