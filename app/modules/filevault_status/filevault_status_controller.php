<?php

/**
 * Filevault status module class
 *
 * @package munkireport
 * @author
 **/
class Filevault_status_controller extends Module_controller
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
        echo "You've loaded the filevault_status module!";
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

        $filevault_escrow = new Filevault_escrow_model($serial_number);
        $filevault_status = new Filevault_status_model($serial_number);
        $disk_report = new Disk_report_model($serial_number);

        // Add relevant keys to escrow object
        $filevault_escrow->rs['filevault_status'] = $filevault_status->rs['filevault_status'];
        $filevault_escrow->rs['filevault_users'] = $filevault_status->rs['filevault_users'];
        
        $obj->view('json', array('msg' => $filevault_escrow->rs));
    }

} // END class default_module
