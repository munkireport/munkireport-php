<?php
/**
 * power status module class
 *
 * @package munkireport
 * @author
 **/
class Power_controller extends Module_controller
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
        echo "You've loaded the power module!";
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
        $power = new Power_model($serial_number);
        $temp_format = conf('temperature_unit');
        $power->rs['temp_format'] = $temp_format; // Add the temp format for use in the client tab's JavaScript    
        $obj->view('json', array('msg' => $power->rs));
    }
    
    /**
     * Get Power Statistics
     *
     *
     **/
    public function get_stats()
    {
        $obj = new View();
        
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $pm = new Power_model;
        $out[] = $pm->get_stats();
        $obj->view('json', array('msg' => $out));
    }


    /**
     * Get conditions
     *
     * @return void
     * @author AvB
     **/
    public function conditions()
    {
        
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $queryobj = new Power_model();
        $sql = "SELECT COUNT(CASE WHEN `condition` = 'Normal' OR `condition` = 'Good' THEN 1 END) AS normal,
						COUNT(CASE WHEN `condition` = 'Replace Soon' OR `condition` = 'ReplaceSoon' OR `condition` = 'Fair' THEN 1 END) AS soon,
						COUNT(CASE WHEN `condition` = 'Service Battery' OR `condition` = 'ServiceBattery' OR `condition` = 'Check Battery' THEN 1 END) AS service,
						COUNT(CASE WHEN `condition` = 'Replace Now' OR `condition` = 'ReplaceNow' OR `condition` = 'Poor' THEN 1 END) AS now,
						COUNT(CASE WHEN `condition` = 'No Battery' OR `condition` = 'NoBattery' THEN 1 END) AS missing
			 			FROM power
			 			LEFT JOIN reportdata USING (serial_number)
			 			".get_machine_group_filter();
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
} // END class Power_controller