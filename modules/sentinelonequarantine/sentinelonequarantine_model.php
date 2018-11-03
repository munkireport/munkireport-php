<?php

use CFPropertyList\CFPropertyList;

class Sentinelonequarantine_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'sentinelonequarantine'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['path'] = '';
        $this->rs['uuid'] = '';
       
        if ($serial) {
            $this->retrieve_record($serial);
        } 
        
    $this->serial_number = $serial;
    }
    
    
    // ------------------------------------------------------------------------
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
			'path' => '',
			'uuid' => ''
		);
		
		foreach ($myList as $device) {
			 			
			// Make sure path is set
			$device['path'] = isset($device['path']) ? $device['path'] : '';
            
			// Make sure uuid is set
			$device['uuid'] = isset($device['uuid']) ? $device['uuid'] : '';
            
			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $device))
				{
					$this->rs[$key] = $device[$key];
				}
			}
			
			// Save device
			$this->id = '';
			$this->save();
		}
	}

}
