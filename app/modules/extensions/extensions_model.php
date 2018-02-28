<?php

use CFPropertyList\CFPropertyList;

class Extensions_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'extensions'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['name'] = '';
		$this->rs['bundle_id'] = '';
		$this->rs['version'] = '';
		$this->rs['path'] = '';
		$this->rs['developer'] = '';
		$this->rs['teamid'] = '';
		$this->rs['executable'] = '';

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

    
     /**
     * Get bundle IDs for widget
     *
     **/
     public function get_bundle_ids()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN name <> '' AND bundle_id IS NOT NULL THEN 1 END) AS count, name 
                FROM extensions
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY name
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->name = $obj->name ? $obj->name : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }

     public function get_developer()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN NAME <> '' AND developer IS NOT NULL THEN 1 END) AS count, developer 
                FROM extensions
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY developer
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->developer = $obj->developer ? $obj->developer : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }

      public function get_teamid()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN NAME <> '' AND teamid IS NOT NULL THEN 1 END) AS count, teamid 
                FROM extensions
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY teamid
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->teamid = $obj->teamid ? $obj->teamid : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }
   
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
			'bundle_id' => '',
			'version' => '',
			'path' => '',
			'developer' => '',
			'teamid' => '',
			'executable' => ''
		);
		
		foreach ($myList as $kext) {
			// Check if we have a bundle ID
			if( ! array_key_exists("bundle_id", $kext)){
				continue;
			}
            
			// Get extension name
			$path_array = explode("/", $kext['path']);
			$kext['name'] = str_replace(".kext","",array_pop($path_array));
            
			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $kext))
				{
					$this->rs[$key] = $kext[$key];
				}
			}
			
			// Save kext
			$this->id = '';
			$this->save();
		}
	}
}
