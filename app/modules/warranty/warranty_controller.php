<?php

/**
 * Warranty module class
 *
 * @package munkireport
 * @author AvB
 **/
class Warranty_controller extends Module_controller
{
    public function __construct()
    {
        // No authentication, the client needs to get here

        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    public function index()
    {
        echo "You've loaded the warranty module!";
    }

    /**
     * Force recheck warranty
     *
     * @return void
     * @author AvB
     **/
    public function recheck_warranty($serial = '')
    {
        // Authenticate
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        if (authorized_for_serial($serial)) {
            $warranty = new Warranty_model($serial);
            $warranty->check_status($force = true);
        }

        redirect("clients/detail/$serial");
    }

    /**
     * Get estimate_manufactured_date
     *
     * @return void
     * @author
     **/
    public function estimate_manufactured_date($serial_number = '')
    {
        // Authenticate
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        require_once(conf('application_path') . "helpers/warranty_helper.php");
        $out = array('date' => estimate_manufactured_date($serial_number));
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    
    /**
     * Get Warranty statistics
     *
     * @param bool $alert Filter on 30 days
     **/
    public function get_stats($alert = false)
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authorized')));
        } else {
            $wm = new Warranty_model();
            $obj->view('json', array('msg' => $wm->get_stats($alert)));
        }
    }

    /**
     * Generate age data for age widget
     *
     * @author AvB
     **/
    public function age()
    {
        // Authenticate
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        $out = array();
        $warranty = new Warranty_model();

        // Time calculations differ between sql implementations
        switch ($warranty->get_driver()) {
            case 'sqlite':
                $agesql = "CAST(strftime('%Y.%m%d', 'now') - strftime('%Y.%m%d', purchase_date) AS INT)";
                break;
            case 'mysql':
                $agesql = "TIMESTAMPDIFF(YEAR,purchase_date,CURDATE())";
                break;
            default: // FIXME for other DB engines
                $agesql = "SUBSTR(purchase_date, 1, 4)";
        }

        // Get filter for business units
        $where = get_machine_group_filter();

        $sql = "SELECT count(1) as count, 
                $agesql as age FROM warranty 
                LEFT JOIN reportdata USING (serial_number)
                $where
                AND $agesql IS NOT NULL 
                GROUP by age 
                ORDER BY age ASC;";
        $cnt = 0;
        
        foreach ($warranty->query($sql) as $obj) {
            $obj->age = $obj->age ? $obj->age : '<1';
            $out[] = array('label' => $obj->age, 'count' => intval($obj->count));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class Warranty_module
