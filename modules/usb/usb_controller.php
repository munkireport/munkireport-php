<?php 

/**
 * USB status module class
 *
 * @package munkireport
 * @author miqviq
 **/
class Usb_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author miqviq
	 *
	 **/
	function index()
	{
		echo "You've loaded the usb module!";
	}

	/**
     * Get USB device names for widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
     public function get_usb_devices()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $usb = new Usb_model;
        $obj->view('json', array('msg' => $usb->get_usb_devices()));
     }
    
     /**
     * Get USB device types for widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
     public function get_usb_types()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $usb = new Usb_model;
        $obj->view('json', array('msg' => $usb->get_usb_types()));
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
        
        $queryobj = new Usb_model();
        
        $sql = "SELECT name, type, manufacturer, vendor_id, device_speed, internal, media, bus_power, bus_power_used, extra_current_used, usb_serial_number
                        FROM usb 
                        WHERE serial_number = '$serial_number'";
        
        $usb_tab = $queryobj->query($sql);

        $usb = new Usb_model;
        //$obj->view('json', array('msg' => $usb->retrieve_records($serial_number)));
        $obj->view('json', array('msg' => current(array('msg' => $usb_tab)))); 
    }
		
} // END class Usb_controller
