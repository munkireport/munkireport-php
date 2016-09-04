<?php

/**
 * Machine module class
 *
 * @package munkireport
 * @author
 **/
class Machine_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    function __construct()
    {
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

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
        echo "You've loaded the hardware module!";
    }
    
    /**
     * Get duplicate computernames
     *
     *
     **/
    public function get_duplicate_computernames()
    {
        $machine = new Machine_model();
        $obj = new View();
        $obj->view('json', array('msg' => $machine->get_duplicate_computernames()));
    }
    
    /**
     * Get model statistics
     *
     **/
    public function get_model_stats()
    {
        $machine = new Machine_model();
        $obj = new View();
        $obj->view('json', array('msg' => $machine->get_model_stats()));
    }


    /**
     * Get machine data for a particular machine
     *
     * @return void
     * @author
     **/
    function report($serial_number = '')
    {
        $machine = new Machine_model($serial_number);
        $obj = new View();
        $obj->view('json', array('msg' => $machine->rs));
    }

    /**
     * Return new clients
     *
     * @return void
     * @author
     **/
    function new_clients()
    {
        $lastweek = time() - 60 * 60 * 24 * 7;
        $out = array();
        $machine = new Machine_model();
        new Reportdata_model;

        $filter = get_machine_group_filter('AND');

        $sql = "SELECT machine.serial_number, computer_name, reg_timestamp
			FROM machine 
			LEFT JOIN reportdata USING (serial_number)
			WHERE reg_timestamp > $lastweek
			$filter
			ORDER BY reg_timestamp DESC";

        foreach ($machine->query($sql) as $obj) {
            $out[]  = $obj;
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }

    /**
     * Return json array with memory configuration breakdown
     *
     * @param string $format Format output. Possible values: flotr, none
     * @author AvB
     **/
    function get_memory_stats($format = 'none')
    {
        $out = array();

        // Legacy loop to do sort in php
        $tmp = array();
        $machine = new Machine_model();
        foreach ($machine->get_memory_stats() as $obj) {
        // Take care of mixed entries (string or int)
            if (isset($tmp[$obj->physical_memory])) {
                $tmp[$obj->physical_memory] += $obj->count;
            } else {
                $tmp[$obj->physical_memory] = $obj->count;
            }
        }
        
        switch ($format) {
            case 'flotr':
                krsort($tmp);

                $cnt = 0;
                foreach ($tmp as $mem => $memcnt) {
                    $out[] = array('label' => $mem . ' GB', 'data' => array(array(intval($memcnt), $cnt++)));
                }
                break;
                
            default:
                foreach ($tmp as $mem => $memcnt) {
                    $out[] = array('physical_memory' => $mem, 'count' => $memcnt);
                }

        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Return json array with hardware configuration breakdown
     *
     * @author AvB
     **/
    function hw()
    {
        $out = array();
        $machine = new Machine_model();
        $sql = "SELECT machine_name, count(1) as count 
			FROM machine
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter()."
			GROUP BY machine_name 
			ORDER BY count DESC";
        $cnt = 0;
        foreach ($machine->query($sql) as $obj) {
            $out[] = array('label' => $obj->machine_name, 'data' => array(array($cnt++, intval($obj->count))));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Return json array with os breakdown
     *
     * @author AvB
     **/
    function os()
    {
        $out = array();
        $machine = new Machine_model();
        $sql = "SELECT count(1) as count, os_version 
				FROM machine
				LEFT JOIN reportdata USING (serial_number)
				".get_machine_group_filter()."
				GROUP BY os_version
				ORDER BY os_version ASC";

        $os_array = array();
        foreach ($machine->query($sql) as $obj) {
            $obj->os_version = $obj->os_version ? $obj->os_version : '0.0.0';
            $os_array[$obj->os_version] = $obj->count;
        }

        // Convert to flotr array
        $cnt = 0;
        foreach ($os_array as $os => $count) {
            $os = $os == '0' ? 'Unknown' : $os;
            $out[] = array('label' => $os, 'data' => array(array(intval($count), $cnt++)));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class Machine_controller
