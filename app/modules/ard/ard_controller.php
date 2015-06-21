<?php 

/**
 * Ard_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Ard_controller extends Module_controller
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
		echo "You've loaded the ard module!";
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

        $ard = new Ard_model($serial_number);
        $obj->view('json', array('msg' => $ard->rs));
    }
} // END class Ard_controller
