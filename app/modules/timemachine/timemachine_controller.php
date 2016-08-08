<?php 
/**
 * Timemachine module class
 *
 * @package munkireport
 * @author AvB
 **/
class Timemachine_controller extends Module_controller
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
		echo "You've loaded the timemachine module!";
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
            return;
        }
		
        $model = new Timemachine_model;
		foreach($model->retrieve_records($serial_number) as $record)
		{
			$out[] = $record->rs;
		}
		$obj->view('json', array('msg' => $out));

    }

    /**
     * Get timemachine stats
     *
     * @return void
     * @author 
     **/
    function get_stats($hours = 24)
    {
        $obj = new View();

        if( ! $this->authorized())
        {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $timemachine = new Timemachine_model;
        $obj->view('json', array('msg' => $timemachine->get_stats($hours)));
    }

	
} // END class Timemachine_controller
