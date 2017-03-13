<?php
/**
 * Timemachine module class
 *
 * @package munkireport
 * @author AvB
 **/
class Timemachine_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
    }
    /**
     * Default method
     *
     * @author
     **/
    public function index()
    {
        echo "You've loaded the timemachine module!";
    }

    
    /**
     * Retrieve data in json format
     *
     **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $queryobj = new Timemachine_model();
        
        $sql = "SELECT last_success, last_failure, last_failure_msg, duration, result, last_destination_id, volume_display_name, time_capsule_display_name, server_display_name, network_url, mount_point, last_known_encryption_state, bytes_available, bytes_used, destination_id, consistency_scan_date, date_of_latest_warning, last_configuration_trace_date, earliest_snapshot_date, latest_snapshot_date, snapshot_count, auto_backup, always_show_deleted_backups_warning, skip_system_files, mobile_backups, is_network_destination, exclude_by_path, skip_paths, destination_uuids, root_volume_uuid, host_uuids, destination_uuids, alias_volume_name, snapshot_dates
                        FROM timemachine 
                        WHERE serial_number = '$serial_number'";
        
        $timemachine_tab = $queryobj->query($sql);

        $timemachine = new Timemachine_model;
        $obj->view('json', array('msg' => current(array('msg' => $timemachine_tab)))); 
    }

    /**
     * Retrieve data in json format
     *
     * @return void
     * @author
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $timemachine = new Timemachine_model($serial_number);
        $obj->view('json', array('msg' => $timemachine->rs));
    }

    /**
     * Get timemachine stats
     *
     * @return void
     * @author
     **/
    public function get_stats($hours = 24)
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $timemachine = new Timemachine_model;
        $obj->view('json', array('msg' => $timemachine->get_stats($hours)));
    }
    
} // END class Timemachine_controller
