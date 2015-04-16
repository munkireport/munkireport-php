<?php 
/**
 * Servermetrics list module class
 *
 * @package munkireport
 * @author AvB
 **/
class Servermetrics_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
	}
	/**
	 * Default method
	 *
	 * @author
	 **/
	function index()
	{
		echo "You've loaded the servermetrics module!";
	}

    /**
     * Retrieve data in json format
     *
     * @return void
     * @author 
     **/
    function get_data($serial_number = '', $hours = 24)
    {
        $obj = new View();

        if( ! $this->authorized())
        {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $servermetrics = new Servermetrics_model;
        $obj->view('json', array('msg' => $servermetrics->get_data($serial_number, $hours)));
    }

    /**
     * Show report
     *
     * @author AvB
     **/
    function report()
    {
        if( ! $this->authorized())
        {
            redirect('auth/login');
        }

        $data['page'] = '';
        $obj = new View();
        $obj->view('report', $data, $this->view_path);
    }

	
} // END class Servermetrics_controller
