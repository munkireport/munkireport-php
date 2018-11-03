<?php

/**
 * Directory Service status module class
 *
 * @package munkireport
 * @author
 **/
class Directory_service_controller extends Module_controller
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
     * @author gmarnin
     **/
    public function index()
    {
        echo "You've loaded the directoryservice module!";
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

        $dm = new Directory_service_model($serial_number);
        $obj->view('json', array('msg' => $dm->rs));
    }
    
    /**
     * Get bound Statistics
     *
     *
     **/
    public function get_bound_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $dm = new Directory_service_model();
        $obj->view('json', array('msg' => $dm->get_bound_stats()));
    }

    /**
     * Get Modified Computer names
     *
     *
     **/
    public function get_modified_computernames()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $dm = new Directory_service_model();
        $obj->view('json', array('msg' => $dm->get_modified_computernames()));
    }
} // END class default_module
