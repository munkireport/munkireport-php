<?php

/**
 * usage_stats module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Usage_stats_controller extends Module_controller
{

	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author AvB
	 *
	 **/
	function index()
	{
		echo "You've loaded the usage_stats module!";
	}

	/**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $usage = new Usage_stats_model($serial_number);
        $obj->view('json', array('msg' => $usage->rs));
    }

} // END class Usage_stats_controller
