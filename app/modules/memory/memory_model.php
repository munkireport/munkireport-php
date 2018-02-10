<?php

use CFPropertyList\CFPropertyList;

class Memory_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'memory'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
        $this->rs['name'] = '';
        $this->rs['dimm_size'] = '';
		$this->rs['dimm_speed'] = '';
		$this->rs['dimm_type'] = '';
		$this->rs['dimm_status'] = '';
		$this->rs['dimm_manufacturer'] = '';
		$this->rs['dimm_part_number'] = '';
		$this->rs['dimm_serial_number'] = '';
		$this->rs['dimm_ecc_errors'] = '';
		$this->rs['global_ecc_state'] = 0; // can be 0, 1, or 2
		$this->rs['is_memory_upgradeable'] = 0; // true/false

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('serial_number');
		$this->idx[] = array('name');
		$this->idx[] = array('dimm_size');
		$this->idx[] = array('dimm_speed');
		$this->idx[] = array('dimm_type');
		$this->idx[] = array('dimm_status');
		$this->idx[] = array('dimm_manufacturer');
		$this->idx[] = array('dimm_part_number');
		$this->idx[] = array('dimm_serial_number');
		$this->idx[] = array('dimm_ecc_errors');
		$this->idx[] = array('global_ecc_state');
		$this->idx[] = array('is_memory_upgradeable');
        
		// Create table if it does not exist
		//$this->create_table();

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
		
		// Delete previous set        
		$this->deleteWhere('serial_number=?', $this->serial_number);

		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();
        		
		$typeList = array(
			'name' => '',
			'dimm_size' => '',
			'dimm_speed' => '',
			'dimm_type' => '',
			'dimm_status' => '',
			'dimm_manufacturer' => '',
			'dimm_part_number' => '',
			'dimm_serial_number' => '',
			'dimm_ecc_errors' => '',
			'global_ecc_state' => 0,
			'is_memory_upgradeable' => 0 // True or False
        );
		
		foreach ($myList as $memstick) {
            
            // Check if we have a name
			if( ! array_key_exists("name", $memstick)){
				continue;
			}
            
            // Don't process empty VMware memory
			if( array_key_exists("name", $memstick) && substr($memstick['name'], 0, 10) === "RAM slot #" && array_key_exists("dimm_size", $memstick) && $memstick['dimm_size'] == "empty"){
				continue;
			}         
            
            // Don't process empty VMware memory
			if( array_key_exists("name", $memstick) && substr($memstick['name'], 0, 10) === "NVD slot #" && array_key_exists("dimm_size", $memstick) && $memstick['dimm_size'] == "empty"){
				continue;
			}  
            
            // Add manufacturer name and cleanup
            if (array_key_exists("dimm_manufacturer",$memstick)) {
                $memstick['dimm_manufacturer'] = str_replace(array('0x029E','0x014F','0x802C','0x830B','0x80AD','0x02FE','0x0000','0x8394','0x0D9B','0x2C00','0x80CE','0xAD00','0xCE00','0x5105','0x8551'), array('Corsair (0x029E)','Transcend Information (0x014F)','Micron Technology (0x802C)','Nanya (0x830B)','Hynix Semiconductor (0x80AD)','Elpida Memory (0x02FE)','TransIntl (0x0000)','Mushkin (0x8394)','Crucial (0x0D9B)','Micron Technology (0x2C00)','Samsung Electronics (0x80CE)','Hynix Semiconductor (0xAD00)','Samsung Electronics (0xCE00)','Qimonda AG (0x5105)','Qimonda AG (0x8551)'),$memstick['dimm_manufacturer']);
                $memstick['dimm_manufacturer'] = str_replace("000000000000","",$memstick['dimm_manufacturer']);
			}

            
			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
                if(array_key_exists($key, $memstick))
				{
					$this->rs[$key] = $memstick[$key];
				}
			}
            
            
            
            // If empty, null the values
            if(array_key_exists("dimm_size", $memstick) && $memstick['dimm_size'] == "empty"){
                
                $this->rs['dimm_status'] = "empty";
                $this->rs['dimm_size'] = '';
                $this->rs['dimm_speed'] = '';
                $this->rs['dimm_manufacturer'] = '';
                $this->rs['dimm_type'] = '';
                $this->rs['dimm_part_number'] = '';
                $this->rs['dimm_serial_number'] = '';
                $this->rs['dimm_ecc_errors'] = '';
                $this->rs['dimm_type'] = '';
                $this->rs['dimm_type'] = '';
                $this->rs['dimm_type'] = '';               
            }
			
			// Save memory stick data
			$this->id = '';
			$this->save();
		}
	}
}
