<?php

/**
 * Printer module class
 *
 * @package munkireport
 * @author
 **/
class Printer_controller extends Module_controller
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
        echo "You've loaded the printer module!";
    }
    
    /**
     * Get printers for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial = '')
    {

        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $out = array();
        $prm = new Printer_model;
        foreach ($prm->retrieve_records($serial) as $printer) {
            $out[] = $printer->rs;
        }

        $obj->view('json', array('msg' => $out));
    }

    /**
     * Get printer information for printer widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
    public function get_printers()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $printers = new Printer_model;
        $obj->view('json', array('msg' => $printers->get_printers()));
    }
} // END class default_module
