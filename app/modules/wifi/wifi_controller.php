<?php

use Mr\Wifi\Wifi;

/**
 * Wifi_controller class
 *
 * @package wifi
 * @author John Eberle
 **/
class Wifi_controller extends Module_controller
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
        echo "You've loaded the wifi module!";
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

        $this->connectDB();
        $wifi = Wifi::withoutGlobalScope(\Mr\Scope\MachineGroupScope::class)
            ->where('serial_number', '=', $serial_number)->first();
        $obj->view('json', array('msg' => $wifi));
    }
    
    /**
     * Get WiFi information for state widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
    public function get_wifi_state()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        $db = $this->connectDB();

        // TODO: Machine Group Filter
        $wifiStates = $db::table('wifi')->select(
            $db::raw("COUNT(CASE WHEN state = 'running' THEN 1 END) AS connected"),
            $db::raw("COUNT(CASE WHEN state = 'init' THEN 1 END) AS on_not_connected"),
            $db::raw("COUNT(CASE WHEN state = 'sharing' THEN 1 END) AS sharing"),
            $db::raw("COUNT(CASE WHEN state = 'unknown' THEN 1 END) AS unknown"),
            $db::raw("COUNT(CASE WHEN state = 'off' THEN 1 END) AS off")
        )->leftJoin('reportdata', 'wifi.serial_number', '=', 'reportdata.serial_number')->first();

        $obj->view('json', array('msg' => $wifiStates));
    }
    
    /**
     * Get WiFi information for SSID widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
    public function get_wifi_name()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $db = $this->connectDB();
        $wifiNames = $db::table('wifi')
            ->select(
                $db::raw('count(*) as count'),
                'ssid'
            )
            ->leftJoin('reportdata', 'wifi.serial_number', '=', 'reportdata.serial_number')
            ->whereNotNull('wifi.ssid')
            ->groupBy('ssid');

        $obj->view('json', array('msg' => $wifiNames->get()));
    }

    /**
     * Get WiFi information for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_wifi_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $wifi = new wifi_model($serial_number);
        $obj->view('json', array('msg' => $wifi->rs));
    }
} // END class Wifi_controller
