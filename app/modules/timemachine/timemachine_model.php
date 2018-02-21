<?php

use CFPropertyList\CFPropertyList;

class Timemachine_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'timemachine'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['last_success'] = ''; // Datetime of last successful backup
        $this->rs['last_failure'] = ''; // Datetime of last failure
        $this->rs['last_failure_msg'] = ''; // Message of the last failure
        $this->rs['duration'] = 0; // Duration in seconds
        $this->rs['always_show_deleted_backups_warning'] = 0; // bool
        $this->rs['auto_backup'] = 0; // bool
        $this->rs['bytes_available'] = 0; $this->rt['bytes_available'] = 'BIGINT';
        $this->rs['bytes_used'] = 0; $this->rt['bytes_used'] = 'BIGINT';
        $this->rs['consistency_scan_date'] = '';
        $this->rs['date_of_latest_warning'] = '';
        $this->rs['destination_id'] = '';
        $this->rs['destination_uuids'] = ''; $this->rt['destination_uuids'] = 'TEXT';
        $this->rs['last_known_encryption_state'] = '';
        $this->rs['result'] = '';
        $this->rs['root_volume_uuid'] = '';
        $this->rs['snapshot_dates'] = ''; $this->rt['snapshot_dates'] = 'TEXT';
        $this->rs['exclude_by_path'] = ''; $this->rt['exclude_by_path'] = 'TEXT';
        $this->rs['host_uuids'] = '';
        $this->rs['last_configuration_trace_date'] = '';
        $this->rs['last_destination_id'] = '';
        $this->rs['localized_disk_image_volume_name'] = '';
        $this->rs['mobile_backups'] = 0; // bool
        $this->rs['skip_paths'] = ''; $this->rt['skip_paths'] = 'TEXT';
        $this->rs['skip_system_files'] = 0; // bool
        $this->rs['alias_volume_name'] = '';
        $this->rs['earliest_snapshot_date'] = '';
        $this->rs['is_network_destination'] = 0; // bool
        $this->rs['latest_snapshot_date'] = '';
        $this->rs['mount_point'] = '';
        $this->rs['network_url'] = '';
        $this->rs['server_display_name'] = '';
        $this->rs['snapshot_count'] = 0;
        $this->rs['time_capsule_display_name'] = '';
        $this->rs['volume_display_name'] = '';
        $this->rs['destinations'] = 0;
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 2;
        
        // Indexes to optimize queries
        $this->idx[] = array('last_success');
        $this->idx[] = array('last_failure');
        $this->idx[] = array('last_failure_msg');
        $this->idx[] = array('duration');
        $this->idx[] = array('always_show_deleted_backups_warning');
        $this->idx[] = array('auto_backup');
        $this->idx[] = array('bytes_available');
        $this->idx[] = array('bytes_used');
        $this->idx[] = array('consistency_scan_date');
        $this->idx[] = array('date_of_latest_warning');
        $this->idx[] = array('destination_id');
        $this->idx[] = array('last_known_encryption_state');
        $this->idx[] = array('result');
        $this->idx[] = array('root_volume_uuid');
        $this->idx[] = array('host_uuids');
        $this->idx[] = array('last_configuration_trace_date');
        $this->idx[] = array('last_destination_id');
        $this->idx[] = array('localized_disk_image_volume_name');
        $this->idx[] = array('mobile_backups');
        $this->idx[] = array('skip_system_files');
        $this->idx[] = array('alias_volume_name');
        $this->idx[] = array('earliest_snapshot_date');
        $this->idx[] = array('is_network_destination');
        $this->idx[] = array('latest_snapshot_date');
        $this->idx[] = array('mount_point');
        $this->idx[] = array('network_url');
        $this->idx[] = array('server_display_name');
        $this->idx[] = array('snapshot_count');
        $this->idx[] = array('time_capsule_display_name');
        $this->idx[] = array('volume_display_name');
        $this->idx[] = array('destinations');
        
        // Create table if it does not exist
       //$this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    /**
     * Get statistics
     *
     * @return void
     * @author
     **/
    public function get_stats($hours)
    {
        $now = time();
        $today = date('Y-m-d H:i:s', $now - 3600 * 24);
        $week_ago = date('Y-m-d H:i:s', $now - 3600 * 24 * 7);
        $month_ago = date('Y-m-d H:i:s', $now - 3600 * 24 * 30);
        $sql = "SELECT COUNT(1) as total, 
			COUNT(CASE WHEN last_success > '$today' THEN 1 END) AS today, 
			COUNT(CASE WHEN last_success BETWEEN '$week_ago' AND '$today' THEN 1 END) AS lastweek,
			COUNT(CASE WHEN last_success < '$week_ago' THEN 1 END) AS week_plus
			FROM timemachine
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
        return current($this->query($sql));
    }
    

    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     * Author tuxudo
     *
     **/
    public function process($data)
    {
        
        // If data is empty, throw error
        if (! $data) {
            throw new Exception("Error Processing Time Machine Module Request: No data found", 1);
        } else if (substr( $data, 0, 30 ) != '<?xml version="1.0" encoding="' ) { // Else if old style text, process with old text based handler
        
        // Parse log data
        $start = ''; // Start date
        foreach (explode("\n", $data) as $line) {
            $date = substr($line, 0, 19);
            $message = substr($line, 21);
            
            if (preg_match('/^Starting (automatic|manual) backup/', $message)) {
                $start = $date;
            } elseif (preg_match('/^Backup completed successfully/', $message)) {
                if ($start) {
                    $this->duration = strtotime($date) - strtotime($start);
                } else {
                    $this->duration = 0;
                }
                $this->last_success = $date;
            } elseif (preg_match('/^Backup failed/', $message)) {
                $this->last_failure = $date;
                $this->last_failure_msg = $message;
            }
        }
            
        } else { // Else process with new XML handler    
            
            // Process incoming powerinfo.xml
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();
                    
            // Array of ints
            $ints =  array('always_show_deleted_backups_warning', 'auto_backup', 'bytes_available', 'bytes_used', 'mobile_backups', 'skip_system_files', 'is_network_destination', 'snapshot_count');
            
            // Array of elements nested in Destination array
            $nested =  array('backup_alias', 'bytes_available', 'bytes_used', 'consistency_scan_date', 'date_of_latest_warning', 'destination_id', 'last_known_encryption_state', 'result', 'root_volume_uuid');
            
            // Array of booleans
            $bools =  array('always_show_deleted_backups_warning','auto_backup','mobile_backups','skip_system_files','is_network_destination');

            // Translate battery strings to db fields
            $translate = array(
                'latestSnapshotDate' => 'last_success', // The mis-match is correct
                'AlwaysShowDeletedBackupsWarning' => 'always_show_deleted_backups_warning',
                'AutoBackup' => 'auto_backup',
                'BytesAvailable' => 'bytes_available',
                'BytesUsed' => 'bytes_used',
                'ConsistencyScanDate' => 'consistency_scan_date',
                'condition' => 'condition',
                'DateOfLatestWarning' => 'date_of_latest_warning',
                'DestinationID' => 'destination_id',
                'LastKnownEncryptionState' => 'last_known_encryption_state',
                'RESULT' => 'result',
                'RootVolumeUUID' => 'root_volume_uuid',
                'LastConfigurationTraceDate' => 'last_configuration_trace_date',
                'LastDestinationID' => 'last_destination_id',
                'LocalizedDiskImageVolumeName' => 'localized_disk_image_volume_name',
                'MobileBackups' => 'mobile_backups',
                'SkipSystemFiles' => 'skip_system_files',
                'alias_volume_name' => 'alias_volume_name',
                'earliest_snapshot_date' => 'earliest_snapshot_date',
                'is_network_destination' => 'is_network_destination',
                'latestSnapshotDate' => 'latest_snapshot_date',
                'mount_point' => 'mount_point',
                'network_url' => 'network_url',
                'server_display_name' => 'server_display_name',
                'snapshot_count' => 'snapshot_count',
                'time_capsule_display_name' => 'time_capsule_display_name',
                'volume_display_name' => 'volume_display_name'
            );
                        
            // Traverse the xml with translations
            foreach ($translate as $search => $field) {  
                // If key is not empty, save it to the object
                if (! empty($plist[$search]) && ! in_array($field, $nested) && $plist[$search] != "None" && isset($plist[$search])) { 
                    $this->$field = $plist[$search];
                } else if (in_array($field, $nested) && isset($plist["Destinations"][0][$search])){
                    // If a nested element, extract from nested array
                    $this->$field = $plist["Destinations"][0][$search];
                } else if (array_key_exists($search, $plist) && in_array($field, $bools) && $plist[$search] == true){
                    // If true boolean, set accordingly
                    $this->$field = '1';
                } else if (array_key_exists($search, $plist) && in_array($field, $bools) && $plist[$search] == false){
                    // If false boolean, set accordingly
                    $this->$field = '0';
                } else if ( ! array_key_exists($search, $plist) && in_array($field, $bools)){
                    // If boolean and does not exist in plist, set to null
                    $this->$field = null;
                } else if (array_key_exists($search, $plist) && in_array($field, $ints) && $plist[$search] == "0"){
                    // Set the int to 0 if it's 0
                    $this->$field = $plist[$search];
                } else if ( ! array_key_exists($search, $plist) && in_array($field, $ints)){
                    // If int and does not exist in plist, set to null
                    $this->$field = null;
                } else if (in_array($field, $ints)){
                    // Set the int to 0 if it's 0
                    $this->$field = 0;
                } else {  
                    // Else, null the value
                    $this->$field = '';
                }
            }
            
            // Parse log data from the legacy key
            $start = ''; // Start date
            foreach (explode("\n", $plist["legacy_output"]) as $line) {
                $date = substr($line, 0, 19);
                $message = substr($line, 21);

                if (preg_match('/^Starting (automatic|manual) backup/', $message)) {
                    $start = $date;
                } elseif (preg_match('/^Backup completed successfully/', $message)) {
                    if ($start) {
                        $this->duration = strtotime($date) - strtotime($start);
                    } else {
                        $this->duration = 0;
                    }
                    $this->last_success = $date;
                } elseif (preg_match('/^Backup failed/', $message)) {
                    $this->last_failure = $date;
                    $this->last_failure_msg = $message;
                }
            }
            
            // Verify last_success is not blank
            if (array_key_exists("latestSnapshotDate", $plist) && empty($last_success)) {
                $this->last_success = str_replace(" +0000","",$plist["latestSnapshotDate"]);
            }
            
            // Format dates, if they exist
            if (array_key_exists("earliest_snapshot_date", $plist) && empty($last_success)) {
                $this->earliest_snapshot_date = str_replace(" +0000","",$plist["earliest_snapshot_date"]);
            }
            if (array_key_exists("latestSnapshotDate", $plist) && empty($last_success)) {
                $this->latest_snapshot_date = str_replace(" +0000","",$plist["latestSnapshotDate"]);
            }
            
            // Condense arrays into strings after checking if they exist
            if (array_key_exists("Destinations",$plist) && array_key_exists("DestinationUUIDs",$plist["Destinations"][0])){
                $this->destination_uuids = implode(", ", $plist["Destinations"][0]["DestinationUUIDs"]);
            } else {
                $this->destination_uuids = "";
            }
            
            if (array_key_exists("Destinations",$plist) && array_key_exists("SnapshotDates",$plist["Destinations"][0])){
                $this->snapshot_dates = implode(", ", $plist["Destinations"][0]["SnapshotDates"]);
            } else {
                $this->snapshot_dates = "";
            }
            
            if (array_key_exists("ExcludeByPath",$plist)){
                $this->exclude_by_path = implode(", ", $plist["ExcludeByPath"]);
            } else {
                $this->exclude_by_path = "";
            }
            
            if (array_key_exists("HostUUIDs",$plist)){
                $this->host_uuids = implode(", ", $plist["HostUUIDs"]);
            } else {
                $this->host_uuids = "";
            }
            
            if (array_key_exists("SkipPaths",$plist)){
                $this->skip_paths = implode(", ", $plist["SkipPaths"]);
            } else {
                $this->skip_paths = "";
            }
            
            // Set destinations count
            if (array_key_exists("Destinations",$plist)){
                $this->destinations = count($plist["Destinations"]);
            } else {
                $this->destinations = "0";
            }
        }
        
        // Only store if there is data
        if ($this->last_success or $this->last_failure) {
            $this->save();
        }
    }
}
