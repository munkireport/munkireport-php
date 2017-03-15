<?php

/**
 * firmware_escrow_controller class
 *
 * @package firmware_escrow
 * @author tuxudo
 **/
class Firmware_escrow_controller extends Module_controller
{
    public function __construct()
    {
        $this->module_path = dirname(__FILE__);
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the firmware_escrow module!";
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

        $firmware_escrow = new Firmware_escrow_model($serial_number);
        $obj->view('json', array('msg' => $firmware_escrow->rs));
    }
    
} // END class Firmware_escrow_controller
