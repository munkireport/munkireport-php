<?php

/**
 * Displays module class
 *
 * @package munkireport
 * @author Noel B.A.
 **/
class Displays_info_controller extends Module_controller
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
        echo "You've loaded the displays module!";
    }
    
     /**
     * Get displays for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial = '')
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $prm = new Displays_info_model;
            foreach ($prm->retrieve_records($serial) as $display) {
                $out[] = $display->rs;
            }
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    
    /**
     * Get count of  displays
     *
     *
     * @param int $type type 1 is external, type 0 is internal
     **/
    public function get_count($type = 1)
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $dim = new Displays_info_model();
        $obj->view('json', array('msg' => $dim->get_count($type)));
    }
} // END class default_module
