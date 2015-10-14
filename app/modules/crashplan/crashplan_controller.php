<?php 

/**
 * Crashplan_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Crashplan_controller extends Module_controller
{
	function __construct()
	{
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the Crashplan module!";
	}
	
	function listing()
	{
		if( ! $this->authorized())
		{
			redirect('auth/login');
		}
		
		$data['page'] = '';
		$obj = new View();
		$obj->view('crashplan_listing', $data, $this->view_path);
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

        $service = new Crashplan_model;
        $obj->view('json', array('msg' => $service->retrieve_records($serial_number)));
    }


} // END class Crashplan_controller