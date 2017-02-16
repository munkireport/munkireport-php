<?php
class Power_model extends Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'power'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['manufacture_date'] = '';
        $this->rs['design_capacity'] = -9876543;
        $this->rs['max_capacity'] = -9876543;
        $this->rs['max_percent'] = -9876543;
        $this->rs['current_capacity'] = -9876543;
        $this->rs['current_percent'] = -9876543;
        $this->rs['cycle_count'] = -9876543;
        $this->rs['temperature'] = -9876543;
        $this->rs['condition'] = '';
        $this->rs['timestamp'] = -9876543; // Unix time when the report was uploaded
        $this->rs['sleep_prevented_by'] = ''; $this->rt['sleep_prevented_by'] = 'TEXT';
        $this->rs['hibernatefile'] = '';
        $this->rs['schedule'] = '';
        $this->rs['adapter_id'] = '';
        $this->rs['family_code'] = '';
        $this->rs['adapter_serial_number'] = '';
        $this->rs['combined_sys_load'] = '';
        $this->rs['user_sys_load'] = '';
        $this->rs['thermal_level'] = '';
        $this->rs['battery_level'] = '';
        $this->rs['ups_name'] = '';
        $this->rs['active_profile'] = '';
        $this->rs['ups_charging_status'] = '';
        $this->rs['externalconnected'] = '';
        $this->rs['cellvoltage'] = '';
        $this->rs['manufacturer'] = '';
        $this->rs['batteryserialnumber'] = '';
        $this->rs['fullycharged'] = '';
        $this->rs['ischarging'] = '';
        $this->rs['standbydelay'] = -9876543;
        $this->rs['standby'] = -9876543;
        $this->rs['womp'] = -9876543;
        $this->rs['halfdim'] = -9876543;
        $this->rs['gpuswitch'] = -9876543;
        $this->rs['sms'] = -9876543;
        $this->rs['networkoversleep'] = -9876543;
        $this->rs['disksleep'] = -9876543;
        $this->rs['sleep'] = -9876543;
        $this->rs['autopoweroffdelay'] = -9876543;
        $this->rs['hibernatemode'] = -9876543;
        $this->rs['autopoweroff'] = -9876543;
        $this->rs['ttyskeepawake'] = -9876543;
        $this->rs['displaysleep'] = -9876543;
        $this->rs['acwake'] = -9876543;
        $this->rs['lidwake'] = -9876543;
        $this->rs['sleep_on_power_button'] = -9876543;
        $this->rs['autorestart'] = -9876543;
        $this->rs['destroyfvkeyonstandby'] = -9876543;
        $this->rs['powernap'] = -9876543;
        $this->rs['haltlevel'] = -9876543;
        $this->rs['haltafter'] = -9876543;
        $this->rs['haltremain'] = -9876543;
        $this->rs['lessbright'] = -9876543;
        $this->rs['sleep_count'] = -9876543;
        $this->rs['dark_wake_count'] = -9876543;
        $this->rs['user_wake_count'] = -9876543;
        $this->rs['wattage'] = -9876543;
        $this->rs['backgroundtask'] = -9876543;
        $this->rs['applepushservicetask'] = -9876543;
        $this->rs['userisactive'] = -9876543;
        $this->rs['preventuseridledisplaysleep'] = -9876543;
        $this->rs['preventsystemsleep'] = -9876543;
        $this->rs['externalmedia'] = -9876543;
        $this->rs['preventuseridlesystemsleep'] = -9876543;
        $this->rs['networkclientactive'] = -9876543;
        $this->rs['cpu_scheduler_limit'] = -9876543;
        $this->rs['cpu_available_cpus'] = -9876543;
        $this->rs['cpu_speed_limit'] = -9876543;
        $this->rs['ups_percent'] = -9876543;
        $this->rs['timeremaining'] = -9876543;
        $this->rs['instanttimetoempty'] = -9876543;
        $this->rs['voltage'] = -9876543; $this->rt['voltage'] = 'FLOAT';
        $this->rs['permanentfailurestatus'] = -9876543;
        $this->rs['packreserve'] = -9876543;
        $this->rs['avgtimetofull'] = -9876543;
        $this->rs['amperage'] = -9876543; $this->rt['amperage'] = 'FLOAT';
        $this->rs['designcyclecount'] = -9876543;
        $this->rs['avgtimetoempty'] = -9876543;
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 1;
        
        // Indexes to optimize queries
        // MySQL allows for a maximum of 64 indexes per table, not all columns are indexed.
        // Some lesser used, more static columns have been omitted
        foreach (array('manufacture_date','design_capacity','max_capacity','max_percent','current_capacity','current_percent','cycle_count','temperature','condition','timestamp','hibernatefile','active_profile','standbydelay','standby','womp','halfdim','gpuswitch','sms','networkoversleep','disksleep','sleep','autopoweroffdelay','hibernatemode','autopoweroff','ttyskeepawake','displaysleep','acwake','lidwake','sleep_on_power_button','autorestart','destroyfvkeyonstandby','powernap','sleep_count','dark_wake_count','user_wake_count','wattage','backgroundtask','applepushservicetask','userisactive','preventuseridledisplaysleep','preventsystemsleep','externalmedia','preventuseridlesystemsleep','networkclientactive','externalconnected','timeremaining','instanttimetoempty','cellvoltage','voltage','permanentfailurestatus','manufacturer','packreserve','avgtimetofull','batteryserialnumber','amperage','fullycharged','ischarging','designcyclecount','avgtimetoempty') as $item) {
        $this->idx[] = array($item);
        }
        
        // Create table if it does not exist
        $this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    /**
     * Get Power statistics
     *
     *
     **/
    public function get_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN max_percent>89 THEN 1 END) AS success,
                        COUNT(CASE WHEN max_percent BETWEEN 80 AND 89 THEN 1 END) AS warning,
                        COUNT(CASE WHEN max_percent<80 THEN 1 END) AS danger
                        FROM power
                        LEFT JOIN reportdata USING(serial_number)
                        ".get_machine_group_filter();
        return current($this->query($sql));
    }
    
    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        // If data is empty, throw error
        if (! $data) {
            throw new Exception("Error Processing Power Module Request: No data found", 1);
        } else if (substr( $data, 0, 30 ) != '<?xml version="1.0" encoding="' ) { // Else if old style text, process with old text based handler

        // Translate network strings to db fields
        $translate = array(
            'manufacture_date = ' => 'manufacture_date',
            'design_capacity = ' => 'design_capacity',
            'max_capacity = ' => 'max_capacity',
            'current_capacity = ' => 'current_capacity',
            'cycle_count = ' => 'cycle_count',
            'temperature = ' => 'temperature',
            'condition = ' => 'condition');
        // Reset values
        $this->manufacture_date = '';
        $this->design_capacity = 1;
        $this->max_capacity = 1;
        $this->max_percent = 100;
        $this->current_capacity = 0;
        $this->current_percent = 0;
        $this->cycle_count = 0;
        $this->temperature = 0;
        $this->condition = '';
        $this->timestamp = 0;
        // Parse data
        foreach(explode("\n", $data) as $line) {
            // Translate standard entries
            foreach($translate as $search => $field) {
                
                if(strpos($line, $search) === 0) {
                    
                    $value = substr($line, strlen($search));
                    
                    $this->$field = $value;
                    break;
                }
            } 
        } //end foreach explode lines 
            
        } else { // Else process with new XML handler

        // Array of ints for nulling with -9876543
        $ints =  array('standbydelay','standby','womp','halfdim','gpuswitch','sms','networkoversleep','disksleep','sleep','autopoweroffdelay','hibernatemode','autopoweroff','ttyskeepawake','displaysleep','acwake','lidwake','sleep_on_power_button','autorestart','destroyfvkeyonstandby','powernap','haltlevel','haltafter','haltremain','lessbright','sleep_count','dark_wake_count','user_wake_count','wattage','backgroundtask','applepushservicetask','userisactive','preventuseridledisplaysleep','preventsystemsleep','externalmedia','preventuseridlesystemsleep','networkclientactive','cpu_scheduler_limit','cpu_available_cpus','cpu_speed_limit','ups_percent','timeremaining','instanttimetoempty','permanentfailurestatus','packreserve','avgtimetofull','designcyclecount','avgtimetoempty','voltage','amperage','temperature','cycle_count','current_percent','current_capacity','max_percent','max_capacity','design_capacity');
        
        // Process incoming powerinfo.xml
        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);
        $plist = $parser->toArray();
        
        // Translate battery strings to db fields
        $translate = array(
            'ManufactureDate' => 'manufacture_date',
            'DesignCapacity' => 'design_capacity',
            'CurrentCapacity' => 'current_capacity',
            'CycleCount' => 'cycle_count',
            'Temperature' => 'temperature',
            'MaxCapacity' => 'max_capacity',
            'condition' => 'condition',
            'standbydelay' => 'standbydelay',
            'standby' => 'standby',
            'womp' => 'womp',
            'halfdim' => 'halfdim',
            'hibernatefile' => 'hibernatefile',
            'gpuswitch' => 'gpuswitch',
            'sms' => 'sms',
            'networkoversleep' => 'networkoversleep',
            'disksleep' => 'disksleep',
            'sleep' => 'sleep',
            'autopoweroffdelay' => 'autopoweroffdelay',
            'hibernatemode' => 'hibernatemode',
            'autopoweroff' => 'autopoweroff',
            'ttyskeepawake' => 'ttyskeepawake',
            'displaysleep' => 'displaysleep',
            'acwake' => 'acwake',
            'lidwake' => 'lidwake',
            'SleepOn' => 'sleep_on_power_button',
            'powernap' => 'powernap',
            'autorestart' => 'autorestart',
            'DestroyFVKeyOnStandby' => 'destroyfvkeyonstandby',
            'schedule' => 'schedule',
            'haltlevel' => 'haltlevel',
            'haltafter' => 'haltafter',
            'haltremain' => 'haltremain',
            'lessbright' => 'lessbright',
            'SleepCount' => 'sleep_count',
            'DarkWake' => 'dark_wake_count',
            'UserWake' => 'user_wake_count',
            'attage' => 'wattage', // This is spelled correctly
            'AdapterID' => 'adapter_id',
            'FamilyCode' => 'family_code',
            'SerialNumber' => 'adapter_serial_number',
            'CPUSchedulerLimit' => 'cpu_scheduler_limit',
            'CPUAvailableCPUs' => 'cpu_available_cpus',
            'CPUSpeedLimit' => 'cpu_speed_limit',
            'BackgroundTask' => 'backgroundtask',
            'ApplePushServiceTask' => 'applepushservicetask',
            'UserIsActive' => 'userisactive',
            'PreventUserIdleDisplaySleep' => 'preventuseridledisplaysleep',
            'PreventSystemSleep' => 'preventsystemsleep',
            'ExternalMedia' => 'externalmedia',
            'PreventUserIdleSystemSleep' => 'preventuseridlesystemsleep',
            'NetworkClientActive' => 'networkclientactive',
            'combinedlevel' => 'combined_sys_load',
            'user' => 'user_sys_load',
            'battery' => 'battery_level',
            'thermal' => 'thermal_level',
            'UPSName' => 'ups_name',
            'Nowdrawing' => 'active_profile',      
            'UPSPercent' => 'ups_percent',   
            'UPSStatus' => 'ups_charging_status',
            'ExternalConnected' => 'externalconnected',
            'TimeRemaining' => 'timeremaining',
            'InstantTimeToEmpty' => 'instanttimetoempty',
            'CellVoltage' => 'cellvoltage',
            'Voltage' => 'voltage',
            'PermanentFailureStatus' => 'permanentfailurestatus',
            'Manufacturer' => 'manufacturer',
            'PackReserve' => 'packreserve',
            'AvgTimeToFull' => 'avgtimetofull',
            'BatterySerialNumber' => 'batteryserialnumber',
            'AmperagemA' => 'amperage',
            'FullyCharged' => 'fullycharged',
            'IsCharging' => 'ischarging',
            'DesignCycleCount9C' => 'designcyclecount',
            'AvgTimeToEmpty' => 'avgtimetoempty'
        );

        // Traverse the xml with translations
        foreach ($translate as $search => $field) {  
                // If key is not empty, save it to the object
                if (! empty($plist[$search])) {  
                        $this->$field = $plist[$search];
                } else {
                    // Else, check if key is an int  
                    if (in_array($field, $ints) && $plist[$search] != "0"){
                        // Set the int to fake null of -9876543
                        $this->$field = -9876543;
                    } else if (in_array($field, $ints) && $plist[$search] == "0"){
                        // Set the int to fake null of -9876543
                        $this->$field = $plist[$search];
                    } else {  
                        // Else, null the value
                        $this->$field = '';
                    }
                }
            }
        }
          
        // Check if no battery is inserted and adjust values
        if ( $this->condition == "No Battery") {
            $this->condition = 'No Battery';
            $this->manufacture_date = '1980-00-00';
            $this->design_capacity = -9876543;
            $this->max_capacity = -9876543;
            $this->current_capacity = -9876543;
            $this->cycle_count = -9876543;
            $this->temperature = -9876543;
            
        } else {
            
            // Calculate maximum capacity as percentage of original capacity
            if ( $this->design_capacity > 0) {
                $this->max_percent = round(($this->max_capacity / $this->design_capacity * 100 ), 0 );
            } else {
                $this->max_percent = 0;
            }
        
            // Calculate percentage of current maximum capacity
            if ( $this->max_capacity > 0) {
                $this->current_percent = round(($this->current_capacity / $this->max_capacity * 100 ), 0 );
            } else {
                $this->current_percent = 0;
            }	

            // Convert battery manufacture date to calendar date.
            // Bits 0...4 => day (value 1-31; 5 bits)
            // Bits 5...8 => month (value 1-12; 4 bits)
            // Bits 9...15 => years since 1980 (value 0-127; 7 bits)
        
            $ManufactureDate = $this->manufacture_date;
        
            $mfgday = $this->manufacture_date & 31;
            $mfgmonth = ($this->manufacture_date >> 5) & 15;
            $mfgyear = (($this->manufacture_date >> 9) & 127) + 1980;
        
            $this->manufacture_date = sprintf("%d-%02d-%02d", $mfgyear, $mfgmonth, $mfgday);
        }
        
        //timestamp added by the server
        $this->timestamp = time();
        
        // Fix sleep and make sleep_prevented_by
        $sleep_long = $this->sleep;
               
        if (strpos($sleep_long, '(') !== false) {
            preg_match('/\((.*?)\)/s', $sleep_long, $sleep_array);
            $this->sleep = explode(" (", $sleep_long)[0];                
            $this->sleep_prevented_by = preg_replace("/[^A-Za-z0-9 ]/", '',(implode(", ", array_unique(explode(", ", str_replace("sleep prevented by ", "", $sleep_array[1]))))));
        }
        
        // Correct empty UPS percentage
        if ($this->ups_percent == " " || $this->ups_percent == "" || $this->ups_percent == "  ") {
            $this->ups_percent = -9876543;
        } 
        
        // Remove single quotes from active_profile
        $this->active_profile = str_replace("'", "", $this->active_profile);
        
        // Format cell voltages
        if ($this->cellvoltage != '-9876543') {
            $this->cellvoltage = str_replace(array('(',')'), array('',''), $this->cellvoltage);
            $cellvoltagearray = explode(',', $this->cellvoltage);
            $cellvoltageout = array();
            foreach ($cellvoltagearray as $cell) {
                if ($cell !== "0") {
                       array_push($cellvoltageout, ($cell / 1000));
                }
            }
            $this->cellvoltage = implode($cellvoltageout,'v, ');
        }
        
        // Format voltage
        if ($this->voltage !== '-9876543') {
        $this->voltage = ($this->voltage / 1000);
        }
        
        // Format amperage
        if ($this->amperage !== '-9876543') {
        $this->amperage = substr_replace($this->amperage, ".", -3, 0);
        }
        
        // Format manufacturer
        $this->manufacturer = str_replace('"', '', $this->manufacturer);
        
        // Format batteryserialnumber
        $this->batteryserialnumber = str_replace('"', '', $this->batteryserialnumber);
        
        // Clean pmset -g values
        $this->displaysleep = strtok($this->displaysleep, ' ');
        $this->disksleep = strtok($this->disksleep, ' ');
        $this->standby = strtok($this->standby, ' ');
        
        $this->save();
    }
}
