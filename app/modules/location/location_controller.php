<?php

/**
 * location status module class
 *
 * @package munkireport
 * @author
 **/
class Location_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    function index()
    {
        echo "You've loaded the location module!";
    }
    
    /**
    * Retrieve data in json format
    *
    **/
    function get_data($serial_number = '')
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        $location = new Location_model($serial_number);
        $obj->view('json', array('msg' => $location->rs));
    }
    
    // return locations of all macs
    function get_map_data()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        $location = new Location_model();
        $obj->view('json', array('msg' => $location->get_map_data()));
    }
} // END class location_controller
