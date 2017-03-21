<?php 

/**
 * Smart_stats_controller class
 *
 * @package smart_stats
 * @author tuxudo
 **/
class Smart_stats_controller extends Module_controller
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
		echo "You've loaded the smart_stats module!";
	}
    
    /**
     * Retrieve data in json format for client tab
     *
     * @return void
     * @author tuxudo
     **/
    public function get_client_tab_data($serial_number = '')
    {        
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json
            return;
        }

        $queryobj = new Smart_stats_model();
        
        $sql = "SELECT * FROM smart_stats WHERE serial_number = '$serial_number' ORDER BY disk_number";
        
        $smart_stats_tab = $queryobj->query($sql);

        // Add the temperature type to the object for the client tab
        $array_id = (count($smart_stats_tab) -1 );
        while ($array_id > -1) {
             $smart_stats_tab[$array_id]->temperature_unit = conf('temperature_unit');
             $array_id--;
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => current(array('msg' => $smart_stats_tab)))); 
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
            return;
        }

        $smart_stats = new Smart_stats_model($serial_number);
        $obj->view('json', array('msg' => $smart_stats->rs));
    }
} // END class Smart_stats_controller
