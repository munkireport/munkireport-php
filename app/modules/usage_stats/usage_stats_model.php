<?php

use CFPropertyList\CFPropertyList;

class Usage_stats_model extends \Model {

    function __construct($serial='')
	{
		parent::__construct('id', 'usage_stats'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['timestamp'] = 0; $this->rt['timestamp'] = 'BIGINT';
		$this->rs['thermal_pressure'] = '';
		$this->rs['backlight_max'] = 0;
		$this->rs['backlight_min'] = 0;
		$this->rs['backlight'] = 0;
		$this->rs['keyboard_backlight'] = 0;
		$this->rs['ibyte_rate'] = 0.0;
		$this->rs['ibytes'] = 0.0;
		$this->rs['ipacket_rate'] = 0.0;
		$this->rs['ipackets'] = 0.0;
		$this->rs['obyte_rate'] = 0.0;
		$this->rs['obytes'] = 0.0;
		$this->rs['opacket_rate'] = 0.0;
		$this->rs['opackets'] = 0.0;
		$this->rs['rbytes_per_s'] = 0.0;
		$this->rs['rops_per_s'] = 0.0;
		$this->rs['wbytes_per_s'] = 0.0;
		$this->rs['wops_per_s'] = 0.0;
		$this->rs['rbytes_diff'] = 0.0;
		$this->rs['rops_diff'] = 0.0;
		$this->rs['wbytes_diff'] = 0.0;
		$this->rs['wops_diff'] = 0.0;
		$this->rs['package_watts'] = 0.0;
		$this->rs['package_joules'] = 0.0;
		$this->rs['freq_hz'] = 0.0; // CPU
		$this->rs['freq_ratio'] = 0.0; // CPU
		$this->rs['gpu_name'] = '';
		$this->rs['gpu_freq_hz'] = 0.0;
		$this->rs['gpu_freq_mhz'] = 0.0;
		$this->rs['gpu_freq_ratio'] = 0.0;
		$this->rs['gpu_busy'] = 0.0;
		$this->rs['kern_bootargs'] = "";

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('timestamp');
		$this->idx[] = array('backlight_max');
		$this->idx[] = array('backlight_min');
		$this->idx[] = array('backlight');
		$this->idx[] = array('keyboard_backlight');
		$this->idx[] = array('ibyte_rate');
		$this->idx[] = array('ibytes');
		$this->idx[] = array('ipacket_rate');
		$this->idx[] = array('ipackets');
		$this->idx[] = array('obyte_rate');
		$this->idx[] = array('obytes');
		$this->idx[] = array('opacket_rate');
		$this->idx[] = array('opackets');
		$this->idx[] = array('rbytes_per_s');
		$this->idx[] = array('rops_per_s');
		$this->idx[] = array('wbytes_per_s');
		$this->idx[] = array('wops_per_s');
		$this->idx[] = array('rbytes_diff');
		$this->idx[] = array('rops_diff');
		$this->idx[] = array('wbytes_diff');
		$this->idx[] = array('wops_diff');
		$this->idx[] = array('thermal_pressure');
		$this->idx[] = array('package_watts');
		$this->idx[] = array('package_joules');
		$this->idx[] = array('freq_hz');
		$this->idx[] = array('freq_ratio');
		$this->idx[] = array('gpu_name');
		$this->idx[] = array('gpu_freq_hz');
		$this->idx[] = array('gpu_freq_mhz');
		$this->idx[] = array('gpu_freq_ratio');
		$this->idx[] = array('gpu_busy');
		$this->idx[] = array('kern_bootargs');
        
		// Create table if it does not exist
		//$this->create_table();

        if ($serial) {
            $this->retrieve_record($serial);
        }
        
		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

    
	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author tuxudo
	 **/
	function process($plist)
	{
		
		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}

        // Process incoming usage_stats.plist
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$plist = $parser->toArray();
        
        $fields = array('timestamp','thermal_pressure','backlight_max','backlight_min','backlight','keyboard_backlight','ibyte_rate','ibytes','ipacket_rate','ipackets','obyte_rate','obytes','opacket_rate','opackets','rbytes_per_s','rops_per_s','wbytes_per_s','wops_per_s','rbytes_diff','rops_diff','wbytes_diff','wops_diff','package_watts','package_joules','freq_hz','freq_ratio','gpu_name','gpu_freq_hz','gpu_freq_mhz','gpu_freq_ratio','gpu_busy','kern_bootargs');
        
        foreach ($fields as $field) {
            // If key does not exist in $plist, null it
            if ( ! array_key_exists($field, $plist)) {
                $this->$field = null;
            } else {
                $this->$field = $plist[$field];
            }
        }
                    
        // Save the data 
        $this->save();
	}
}