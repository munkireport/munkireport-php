<?php
/**
 * ios_devices module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Ios_devices_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }
    
    /**
    * Default method
    *
    * @author AvB
    **/
    public function index()
    {
        echo "You've loaded the ios_devices module!";
    }
    
    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT serial, device_class, firmware_version_string, build_version, connected, product_type, use_count, region_info, imei, meid, ios_id, family_id, prefpath
                        FROM ios_devices 
                        WHERE serial_number = '$serial_number'";
        
        $queryobj = new Ios_devices_model();
        $ios_devices_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $ios_devices_tab)))); 
    }
} // END class Ios_devices_controller