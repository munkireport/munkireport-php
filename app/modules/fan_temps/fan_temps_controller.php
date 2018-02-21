<?php 

/**
 * Fan_temps_controller class
 *
 * @package fan_temps
 * @author tuxudo
 **/
class Fan_temps_controller extends Module_controller
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
		echo "You've loaded the fan_temps module!";
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

        $queryobj = new Fan_temps_model();
        
        $sql = "SELECT * FROM fan_temps WHERE serial_number = '$serial_number'";
        
        $fan_temps_tab = $queryobj->query($sql);

        // Add the temperature type to the object for the client tab
        $fan_temps_tab[0]->temperature_unit = conf('temperature_unit');
            
        $obj = new View();
        $obj->view('json', array('msg' => current(array('msg' => $fan_temps_tab)))); 
    }

     /**
     * Retrieve data in json format
     *
     * @return void
     * @author tuxudo
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $fan_temps = new Fan_temps_model;
        $obj->view('json', array('msg' => $fan_temps->retrieve_records($serial_number)));
    }
} // END class Fan_temps_controller