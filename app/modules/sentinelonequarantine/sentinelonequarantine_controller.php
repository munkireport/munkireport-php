<?php
/**
 * SentinelOne Quarantine module class
 *
 * @package munkireport
 * @author
 **/
class Sentinelonequarantine_controller extends Module_controller
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
        echo "You've loaded the sentinelonequarantine module!";
    }
    
    /**
     * Get sentinelone_quarantine for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $s1_q = new Sentinelonequarantine_model;
        $obj->view('json', array('msg' => $s1_q->retrieve_records($serial_number)));
    }

} // END class default_module
