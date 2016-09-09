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
        echo "You've loaded the power module!";
    }
    
    /**
     * Get Power Statistics
     *
     *
     **/
    public function get_stats()
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $pm = new Power_model;
            $out[] = $pm->get_stats();
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }


    /**
     * Get conditions
     *
     * @return void
     * @author AvB
     **/
    function conditions()
    {
        
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json
        }

        $queryobj = new Power_model();
        $sql = "SELECT COUNT(CASE WHEN `condition` = 'Normal' THEN 1 END) AS normal,
						COUNT(CASE WHEN `condition` = 'Replace Soon' THEN 1 END) AS soon,
						COUNT(CASE WHEN `condition` = 'Service Battery' THEN 1 END) AS service,
						COUNT(CASE WHEN `condition` = 'Replace Now' THEN 1 END) AS now,
						COUNT(CASE WHEN `condition` = 'No Battery' THEN 1 END) AS missing
			 			FROM power
			 			LEFT JOIN reportdata USING (serial_number)
			 			".get_machine_group_filter();
        $obj = new View();
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
} // END class default_module
