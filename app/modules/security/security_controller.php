<?php
/**
 * Security module class
 *
 * @package munkireport
 * @author
 **/
class Security_controller extends Module_controller
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
        echo "You've loaded the security module!";
    }
    
    /**
     * Get security for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial = '')
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $prm = new Security_model;
            foreach ($prm->retrieve_records($serial) as $security) {
                $out[] = $security->rs;
            }
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class default_module
