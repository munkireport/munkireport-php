<?php 

/**
 * Service_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Service_controller extends Module_controller
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
		echo "You've loaded the service report module!";
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
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $cert = new Certificate_model;
        $obj->view('json', array('msg' => $cert->retrieve_many('serial_number = ?', $serial_number)));
    }


} // END class Service_controller