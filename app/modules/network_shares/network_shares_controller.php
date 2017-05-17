<?php 

/**
 * network shares module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Network_shares_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author tuxudo
	 *
	 **/
	function index()
	{
		echo "You've loaded the network_shares module!";
	}

	/**
     * Get network shares for widget
     *
     * @return void
     * @author tuxudo
     **/
     public function get_network_shares()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $network_shares = new Network_shares_model;
        $obj->view('json', array('msg' => $network_shares->get_network_shares()));
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
            return;
        }

        $queryobj = new Network_shares_model;
        $network_shares_tab = array();
        foreach($queryobj->retrieve_records($serial_number) as $shareEntry) {
            $network_shares_tab[] = $shareEntry->rs;
        }

        $obj->view('json', array('msg' => $network_shares_tab));
     }
		
} // END class Network_shares_controller
