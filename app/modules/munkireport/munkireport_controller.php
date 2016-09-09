<?php

/**
 * Munkireport module class
 *
 * @package munkireport
 * @author
 **/
class Munkireport_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    function index()
    {
        echo "You've loaded the munkireport module!";
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
        }

        $model = new Munkireport_model($serial_number);
        $obj->view('json', array('msg' => $model->rs));
    }
    
    /**
     * Get manifests statistics
     *
     *
     **/
    public function get_manifest_stats()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authorized')));
        } else {
            $mrm = new Munkireport_model();
            $obj->view('json', array('msg' => $mrm->get_manifest_stats()));
        }
    }
    
    /**
    * Get munki versions
     *
     *
     **/
    public function get_versions()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authorized')));
        } else {
            $mrm = new Munkireport_model();
            $obj->view('json', array('msg' => $mrm->get_versions()));
        }
    }
    
    
    /**
     * Get statistics
     *   *
     * @param integer $hours Number of hours to get stats from
     **/
    public function get_stats($hours = 24)
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $mr = new Munkireport_model;
            $out = $mr->get_stats($hours);
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }
} // END class default_module
