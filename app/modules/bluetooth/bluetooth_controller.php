<?php

/**
 * bluetooth status module class
 *
 * @package munkireport
 * @author clburlison
 **/
class Bluetooth_controller extends Module_controller
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
     **/
    public function index()
    {
        echo "You've loaded the bluetooth module!";
    }

    /**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial = '')
    {
        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $out = array();
        $temp = array();
        $bluetooth = new Bluetooth_model;
        foreach ($bluetooth->retrieve_records($serial) as $prefs) {
            $temp[] = $prefs->rs;
        }
        foreach ($temp as $value) {
            $out[$value['device_type']] = $value['battery_percent'];
        }

        $obj->view('json', array('msg' => $out));
    }
    
    /**
     * Get low battery devices
     *
     *
     **/
    public function get_low()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $bt = new Bluetooth_model();
        $obj->view('json', array('msg' => $bt->get_low()));
    }
} // END class default_module
