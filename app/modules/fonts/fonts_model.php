<?php

use CFPropertyList\CFPropertyList;

class Fonts_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'fonts'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['name'] = '';
		$this->rs['enabled'] = 0; // True or False
		$this->rs['type_name'] = '';
		$this->rs['fullname'] = '';
		$this->rs['type_enabled'] = 0; // True or False
		$this->rs['valid'] = 0; // True or False
		$this->rs['duplicate'] = 0; // True or False
		$this->rs['path'] = ''; $this->rt['path'] = 'TEXT';
		$this->rs['type'] = '';
		$this->rs['family'] = '';
		$this->rs['style'] = '';
		$this->rs['version'] = '';
		$this->rs['embeddable'] = 0; // True or False
		$this->rs['outline'] = 0; // True or False
		$this->rs['unique_id'] = '';
		$this->rs['copyright'] = ''; $this->rt['copyright'] = 'TEXT';
		$this->rs['copy_protected'] = 0; // True or False
		$this->rs['description'] = ''; $this->rt['description'] = 'TEXT';
		$this->rs['vendor'] = ''; $this->rt['vendor'] = 'TEXT';
		$this->rs['designer'] = ''; $this->rt['designer'] = 'TEXT';
		$this->rs['trademark'] = ''; $this->rt['trademark'] = 'TEXT';

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('name');
		$this->idx[] = array('type');
		$this->idx[] = array('type_name');
		$this->idx[] = array('family');
		$this->idx[] = array('fullname');
		$this->idx[] = array('style');
		$this->idx[] = array('unique_id');
		$this->idx[] = array('version');
		$this->idx[] = array('enabled');
		$this->idx[] = array('copy_protected');
		$this->idx[] = array('duplicate');
		$this->idx[] = array('embeddable');
		$this->idx[] = array('type_enabled');
		$this->idx[] = array('outline');
		$this->idx[] = array('valid');
        
		// Create table if it does not exist
		//$this->create_table();

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

    
     /**
     * Get font names for widget
     *
     **/
     public function get_fonts()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN type_name <> '' AND type_name IS NOT NULL THEN 1 END) AS count, type_name 
                FROM fonts
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY type_name
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->type_name = $obj->type_name ? $obj->type_name : 'Unknown';
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
			'type' => 'unknown',
			'type_name' => '',
			'family' => '',
			'style' => '',
			'unique_id' => '',
			'version' => '',
			'enabled' => 0, // True or False
			'copy_protected' => 0, // True or False
			'duplicate' => 0,
			'embeddable' => 0,
			'type_enabled' => 0,
			'outline' => 0,
			'valid' => 0,
			'path' => '',
			'vendor' => '',
			'copyright' => '',
			'description' => '',
			'designer' => '',
			'trademark' => ''
		);
        
		foreach ($myList as $device) {
			// Check if we have a name
			if( ! array_key_exists("name", $device)){
				continue;
			}
            
			// Skip system fonts if value is TRUE
			if (!conf('fonts_system')) {
    			if (0 === strpos($device['path'], '/System/Library/Fonts/')){
					continue;
    			}
			}
            
            // Format font type
			if (isset($device['type'])){
			    $device['type'] = str_replace(array('opentype','truetype','postscript','bitmap'), array('OpenType','TrueType','PostScript','Bitmap'), $device['type']);
			}
            
			// Format Unique ID
			if (isset($device['unique_id'])){
			    $device['unique_id'] = trim($device['unique_id'], '.');
			}
            
			// Format Typeface name
			if (isset($device['type_name'])){
			    $device['type_name'] = trim($device['type_name'], '.');
			}
            
			// Format family
			if (isset($device['family'])){
			    $device['family'] = trim($device['family'], '.');
			}
            
			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $device))
				{
					$this->rs[$key] = $device[$key];
				}
			}
			
			// Save font
			$this->id = '';
			$this->save();
		}
	}
}
