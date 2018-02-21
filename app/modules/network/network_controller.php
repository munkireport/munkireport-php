<?php

/**
 * Network module class
 *
 * @package munkireport
 * @author
 **/
class Network_controller extends Module_controller
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
        echo "You've loaded the network module!";
    }

    /**
     * REST interface, returns json with ip address ranges
     * defined in conf('ipv4router')
     * or passed with GET request
     *
     * @return void
     * @author AvB
     **/
    public function routers()
    {
        
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        $router_arr = array();
        
        // See if we're being parsed a request object
        if (array_key_exists('req', $_GET)) {
            $router_arr = (array) json_decode($_GET['req']);
        }

        if (! $router_arr) { // Empty array, fall back on default ip ranges
            $router_arr = conf('ipv4routers', array());
        }
        
        $out = array();
        $reportdata = new Reportdata_model();

        // Compile SQL
        $cnt = 0;
        $sel_arr = array('COUNT(1) as count');
        foreach ($router_arr as $key => $value) {
            if (is_scalar($value)) {
                $value = array($value);
            }
            $when_str = '';
            foreach ($value as $k => $v) {
                $when_str .= sprintf(" WHEN ipv4router LIKE '%s%%' THEN 1", $v);
            }
            $sel_arr[] = "SUM(CASE $when_str ELSE 0 END) AS r${cnt}";
            $cnt++;
        }
        $sql = "SELECT " . implode(', ', $sel_arr) . " FROM network
			LEFT JOIN reportdata USING (serial_number)
			WHERE ipv4router != '(null)' AND ipv4router != ''".get_machine_group_filter('AND');

        // Create Out array
        if ($obj = current($reportdata->query($sql))) {
            $cnt = $total = 0;
            foreach ($router_arr as $key => $value) {
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
    
     /**
     * Retrieve data in json format
     * @author tuxudo
     *
     **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $queryobj = new Network_model();
        
        $sql = "SELECT service, `order`, status, ethernet, clientid, ipv4conf, ipv4ip, ipv4dns, ipv4mask, ipv4router, ipv6conf, ipv6ip, ipv6prefixlen, ipv6router, vlans, activemtu, validmturange, currentmedia, activemedia
                        FROM network 
                        WHERE serial_number = '$serial_number'";
        
        $network_tab = $queryobj->query($sql);

        $network = new Network_model;
        $obj->view('json', array('msg' => current(array('msg' => $network_tab)))); 
    }
} // END class Network_controller
