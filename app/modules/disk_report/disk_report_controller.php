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
     * Retrieve data in json format
     *
     * @return void
     * @author
     **/
    function get_data($serial_number = '')
    {
        $obj = new View();

        if( ! $this->authorized())
        {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        $out = array();
        $model = new Disk_report_model;
        foreach($model->retrieve_records($serial_number) AS $res)
        {
        	$out[] = $res->rs;
        }
        $obj->view('json', array('msg' => $out));
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
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
				$disk_report = new Disk_report_model;
				$out = array();
				$thresholds = conf('disk_thresholds', array('danger' => 5, 'warning' => 10));
				$out['thresholds'] = $thresholds;
				$out['stats'] = $disk_report->get_stats(
					$mount_point,
					$thresholds['danger'],
					$thresholds['warning']);

        $obj->view('json', array('msg' => $out));

	}

} // END class disk_report_module
