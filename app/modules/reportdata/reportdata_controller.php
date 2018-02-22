<?php

/**
 * Reportdata module class
 *
 * @package munkireport
 * @author AvB
 **/
class Reportdata_controller extends Module_controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        header('Access-Control-Allow-Origin: *');
    }

    public function index()
    {
        echo "You've loaded the Reportdata module!";
    }

    /**
     * Get machine groups
     *
     * @author
     **/
    public function get_groups()
    {
        $reportdata = new Reportdata_model();
        $obj = new View();
        $obj->view('json', array('msg' => $reportdata->get_groups($count = true)));
    }
    
    /**
     * Get check-in statistics
     *
     **/
    public function get_lastseen_stats()
    {
        $reportdata = new Reportdata_model();
        $obj = new View();
        $obj->view('json', array('msg' => $reportdata->get_lastseen_stats()));
    }
    
    /**
     * Get uptime statistics
     *
     **/
    public function getUptimeStats()
    {
        $reportdata = new Reportdata_model();
        $obj = new View();
        $obj->view('json', array('msg' => $reportdata->getUptimeStats()));
    }

    /**
     * REST API for retrieving registration dates
     *
     **/
    public function new_clients()
    {
        $reportdata = new Reportdata_model();
        new Machine_model();

        $where = get_machine_group_filter('WHERE', 'r');

        switch ($reportdata->get_driver()) {
            case 'sqlite':
                $sql = "SELECT strftime('%Y-%m', DATE(reg_timestamp, 'unixepoch')) AS date,
						COUNT(*) AS cnt,
						machine_name AS type
						FROM reportdata r
						LEFT JOIN machine m 
							ON (r.serial_number = m.serial_number)
						$where
						GROUP BY date, machine_name
						ORDER BY date";
                break;
            case 'mysql':
                $sql = "SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(reg_timestamp)), '%Y-%m') AS date, 
						COUNT(*) AS cnt,
						machine_name AS type
						FROM reportdata r
						LEFT JOIN machine m 
							ON (r.serial_number = m.serial_number)
                        $where
						GROUP BY date, machine_name
						ORDER BY date";
                break;
            default:
                die('Unknown database driver');
        }
        //echo $sql;
        $dates = array();
        $out = array();
        
        foreach ($reportdata->query($sql) as $event) {
            // Store date
            $d = new DateTime( $event->date );
            $lastDayOfTheMonth = $d->format( 'Y-m-t' );
            
            // Check if this is the first run
            if( ! $dates){
                // Subtract 16 days and format to last day of the month
                $lastDayOfTheMonthBefore = $d->sub(new DateInterval('P16D'))->format( 'Y-m-t' );
                array_push($dates, $lastDayOfTheMonthBefore);
            }
            
            $pos = array_search($lastDayOfTheMonth, $dates);
            if ($pos === false) {
                array_push($dates, $lastDayOfTheMonth);
                $pos = count($dates) - 1;
            }

            $out[$event->type][$pos] = intval($event->cnt);
        }
        
        // Sort machine types
        ksort($out);
        
        // Replace last date with current date
        if(array_pop($dates)){
            array_push($dates, date('Y-m-d'));
        }
        
        // Prepend all types with 0
        foreach($out as $type => $data)
        {
            $out[$type][0] = 0;
        }

        $obj = new View();
        $obj->view('json', array('msg' => array('dates' => $dates, 'types' => $out)));
    }

    /**
     * Flotr2 interface, returns json with ip address ranges
     * defined in conf('ip_ranges')
     * or passed with GET request
     *
     * @return void
     * @author AvB
     **/
    public function ip()
    {
        $ip_arr = array();
        
        // See if we're being parsed a request object
        if (array_key_exists('req', $_GET)) {
            $ip_arr = (array) json_decode($_GET['req']);
        }

        if (! $ip_arr) { // Empty array, fall back on default ip ranges
            $ip_arr = conf('ip_ranges');
        }
        
        $out = array();
        $reportdata = new Reportdata_model();

        // Compile SQL
        $cnt = 0;
        $sel_arr = array('COUNT(1) as count');
        foreach ($ip_arr as $key => $value) {
            if (is_scalar($value)) {
                $value = array($value);
            }
            $when_str = '';
            foreach ($value as $k => $v) {
                $when_str .= sprintf(" WHEN remote_ip LIKE '%s%%' THEN 1", $v);
            }
            $sel_arr[] = "SUM(CASE $when_str ELSE 0 END) AS r${cnt}";
            $cnt++;
        }
        $sql = "SELECT " . implode(', ', $sel_arr) . "
				FROM reportdata "
                .get_machine_group_filter();

        // Create Out array
        if ($obj = current($reportdata->query($sql))) {
            $cnt = $total = 0;
            foreach ($ip_arr as $key => $value) {
                $col = 'r' . $cnt++;

                $out[] = array('key' => $key, 'cnt' => intval($obj->$col));

                $total += $obj->$col;
            }

            // Add Remaining IP's as other
            if ($obj->count - $total) {
                $out[] = array('key' => 'Other', 'cnt' => $obj->count - $total);
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class Reportdata_controller
