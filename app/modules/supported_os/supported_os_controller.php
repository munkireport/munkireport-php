<?php

/**
 * Supported_os module class
 *
 * @package munkireport
 * @author AvB
 **/
class Supported_os_controller extends Module_controller
{
    public function __construct()
    {
        // No authentication, the client needs to get here

        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    public function index()
    {
        echo "You've loaded the supported_os module!";
    }

    /**
     * Return json array with os breakdown
     *
     * @author AvB, tweaked by tuxudo
     **/
    public function os()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $out = array();
        $machine = new Supported_os_model();
        $sql = "SELECT count(1) as count, highest_supported
				FROM supported_os
				LEFT JOIN reportdata USING (serial_number)
				".get_machine_group_filter()."
				GROUP BY highest_supported
				ORDER BY highest_supported DESC";

        foreach ($machine->query($sql) as $obj) {
            $obj->highest_supported = $obj->highest_supported ? $obj->highest_supported : '0';
            $out[] = array('label' => $obj->highest_supported, 'count' => intval($obj->count));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    
    
} // END class Supported_os_module
