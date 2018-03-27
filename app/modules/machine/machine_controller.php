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
    public function __construct()
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
    public function index()
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
    public function get_model_stats($summary="")
    {
        $machine = new Machine_model();
        $obj = new View();
        $obj->view('json', array('msg' => $machine->get_model_stats($summary)));
    }


    /**
     * Get machine data for a particular machine
     *
     * @return void
     * @author
     **/
    public function report($serial_number = '')
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
    public function new_clients()
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
    public function get_memory_stats($format = 'none')
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
                    $out[] = array('label' => $mem, 'count' => $memcnt);
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
    public function hw()
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
            $out[] = array('label' => $obj->machine_name, 'count' => intval($obj->count));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Return json array with os breakdown
     *
     * @author AvB
     **/
    public function os()
    {
        $out = array();
        $machine = new Machine_model();
        $sql = "SELECT count(1) as count, os_version
				FROM machine
				LEFT JOIN reportdata USING (serial_number)
				".get_machine_group_filter()."
				GROUP BY os_version
				ORDER BY os_version DESC";

        foreach ($machine->query($sql) as $obj) {
            $obj->os_version = $obj->os_version ? $obj->os_version : '0';
            $out[] = array('label' => $obj->os_version, 'count' => intval($obj->count));
        }


        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    /**
     * Return json array with os build breakdown
     *
     * @author AkB
     **/
    public function osbuild()
    {
        $out = array();
        $machine = new Machine_model();
        $sql = "SELECT count(1) as count, buildversion
        FROM machine
        LEFT JOIN reportdata USING (serial_number)
        ".get_machine_group_filter()."
        GROUP BY buildversion
        ORDER BY buildversion DESC";

        foreach ($machine->query($sql) as $obj) {
            $obj->buildversion = $obj->buildversion ? $obj->buildversion : '0';
            $out[] = array('label' => $obj->buildversion, 'count' => intval($obj->count));
        }


        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class Machine_controller
