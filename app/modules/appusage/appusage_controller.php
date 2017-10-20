<?php

/**
 * Appusage_controller class
 *
 * @package munkireport
 * @author @tuxudo
 **/
class Appusage_controller extends Module_controller
{
    public function __construct()
    {
        // No authentication, the client needs to get here

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
        echo "You've loaded the appusage report module!";
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
        }

        $appusage = new Appusage_model;
        $obj->view('json', array('msg' => $appusage->retrieve_records($serial_number)));
    }
    
     /**
     * Retrieve data in json format for app launch widget
     *
     * @author tuxudo
     **/
    public function get_applaunch()
    {

        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $applaunches = new Appusage_model;
        $obj->view('json', array('msg' => $applaunches->get_applaunch()));
    }

} // END class Appusage_controller