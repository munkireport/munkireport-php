<?php 

/**
 * Memory module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Memory_controller extends Module_controller
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
		echo "You've loaded the memory module!";
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
        
        $queryobj = new Memory_model();
        
        $sql = "SELECT name, dimm_size, dimm_speed, dimm_type, dimm_status, dimm_manufacturer, dimm_part_number, dimm_serial_number, dimm_ecc_errors, global_ecc_state, is_memory_upgradeable
                        FROM memory 
                        WHERE serial_number = '$serial_number'";
        
        $memory_tab = $queryobj->query($sql);

        $memory = new Memory_model;
        $obj->view('json', array('msg' => current(array('msg' => $memory_tab)))); 
    }
		
} // END class Memory_controller
