<?php

/**
 * Ibridge module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Ibridge_controller extends Module_controller
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
		echo "You've loaded the ibridge module!";
	}
    
     /**
     * Get iBridge models for widget
     *
     * @return void
     * @author tuxudo
     **/
     public function get_ibridge()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $ibridge = new Ibridge_model;
        $obj->view('json', array('msg' => $ibridge->get_ibridge()));
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
        
        $sql = "SELECT model_name, model_identifier, build, ibridge_serial_number, boot_uuid, marketing_name
                    FROM ibridge 
                    WHERE serial_number = '$serial_number'";
        
        $queryobj = new Ibridge_model();
        $ibridge_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $ibridge_tab)))); 
    }
} // END class Ibridge_controller
