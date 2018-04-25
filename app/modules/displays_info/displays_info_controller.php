<?php

/**
 * Displays module class
 *
 * @package munkireport
 * @author Noel B.A.
 **/
class Displays_info_controller extends Module_controller
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
        echo "You've loaded the displays_info module!";
    }
    
     /**
     * Get displays for serial_number
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
        
        $queryobj = new Displays_info_model();
        
        $sql = "SELECT vendor, type, display_type, display_serial, manufactured, native, ui_resolution, current_resolution, color_depth, connection_type, online, main_display, display_asleep, retina, mirror, mirror_status, interlaced, rotation_supported, television, ambient_brightness, automatic_graphics_switching, virtual_device, edr_supported, edr_enabled, edr_limit,  dp_dpcd_version, dp_current_bandwidth, dp_current_lanes, dp_current_spread, dp_hdcp_capability, dp_max_bandwidth, dp_max_lanes, dp_max_spread, dynamic_range, dp_adapter_firmware_version, timestamp, model
                FROM displays 
                WHERE serial_number = '$serial'";
        
        $displays_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $displays_tab)))); 
    }
    
    /**
     * Get count of displays
     *
     * @param int $type type 1 is external, type 0 is internal
     **/
    public function get_count($type = 1)
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $dim = new Displays_info_model();
        $obj->view('json', array('msg' => $dim->get_count($type)));
    }
} // END class displays_info
