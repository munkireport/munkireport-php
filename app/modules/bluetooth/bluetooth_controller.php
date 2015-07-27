<?php 

/**
 * bluetooth status module class
 *
 * @package munkireport
 * @author
 **/
class Bluetooth_controller extends Module_controller
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
		echo "You've loaded the bluetooth module!";
	}

	/**
     * Retrieve data in json format
     *
     **/
    function get_data($serial_number = '')
    {
        $obj = new View();

        if( ! $this->authorized())
        {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $bt = new Bluetooth_model($serial_number);
        $obj->view('json', array('msg' => $bt->rs));
    }
	
} // END class default_module
