<?php
class Usb_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'usb'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['name'] = '';
		$this->rs['type'] = ''; // Mouse, Trackpad, Hub, etc.
		$this->rs['manufacturer'] = '';
		$this->rs['vendor_id'] = '';
		$this->rs['device_speed'] = ''; // Speed
		$this->rs['internal'] = 0; // True or False
		$this->rs['media'] = 0; // True or False

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Create table if it does not exist
		$this->create_table();

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author miqviq
	 **/
	function process($plist)
	{
		
		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}
		
		// Delete previous set
		$this->delete_where('serial_number=?', $this->serial_number);

		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();
		
		$typeList = array(
			'name' => '',
			'type' => 'unknown', // Mouse, Trackpad, Hub, etc.
			'manufacturer' => '',
			'vendor_id' => '',
			'device_speed' => '', // Speed
			'internal' => 0, // True or False
			'media' => 0, // True or False
			
		);
		
		foreach ($myList as $device) {
			// Check if we have a name
			if( ! isset($device['name'])){
				continue;
			}
			// Check for BUS (TODO: other bus identifiers?)
			if ($device['name'] == 'USB30Bus') {
				continue;
			}
			
			// Skip internal devices (?)
			if ($device['internal']){
				continue;
			}
			
			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $device))
				{
					$this->rs[$key] = $device[$key];
				}
			}
			
			// Determine type
			if($this->media)
			{
				$this->type = 'drive';
				if($this->name == 'Mass Storage')
				{
					$this->type = 'thumbdrive';
				}
			}
			elseif(strpos($this->name, 'Mouse') !== False)
			{
				$this->type = 'mouse';
			}
			elseif(strpos($this->name, 'Keyboard') !== False)
			{
				$this->type = 'keyboard';
			}
			elseif(strpos($this->name, 'iPhone') !== False)
			{
				$this->type = 'iphone';
			}
			
			// Save device
			$this->id = '';
			$this->save();
		}

		throw new Exception("Error Processing Request", 1);
	}
}
