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
		$this->rs['bus_power'] = 0; // True or False
		$this->rs['bus_power_used'] = 0; // True or False
		$this->rs['extra_current_used'] = 0; // True or False

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('name');
		$this->idx[] = array('type');
		$this->idx[] = array('manufacturer');
		$this->idx[] = array('vendor_id');
		$this->idx[] = array('device_speed');
		$this->idx[] = array('internal');
		$this->idx[] = array('bus_power');
		$this->idx[] = array('bus_power_used');
        
		// Create table if it does not exist
		$this->create_table();

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author miqviq, revamped by tuxudo
	 **/
	function process($plist)
	{
		
		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}
		
		// Delete previous set
        $this->deleteWhere('serial_number=?', $this->serial_number);

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
			'bus_power' => 0,
			'bus_power_used' => 0,
			'extra_current_used' => 0
		);
		
		foreach ($myList as $device) {
			// Check if we have a name
			if( ! array_key_exists("name",$device)){
				continue;
			}
            
			// Check for USB Bus, and exclude
			if ($device['name'] == 'USB30Bus' || $device['name'] == 'USB20Bus' || $device['name'] == 'USB11Bus' || $device['name'] == 'USBBus' || $device['name'] == 'UHCI Root Hub Simulation' || $device['name'] == 'EHCI Root Hub Simulation') {
				continue;
			}
            
			// Skip internal devices if value is TRUE
			if (!conf('usb_internal')) {
    			if ($device['internal']){
					continue;
    			}
			}
			 
            // Adjust names
			$device['name'] = str_replace(array('bluetooth_device','hub_device','composite_device'), array('Bluetooth USB Host Controller','USB Hub','Composite Device'), $device['name']);
            
            // Adjust USB speeds
			if (array_key_exists("device_speed",$device)) {
				$device['device_speed'] = str_replace(array('low_speed','full_speed','high_speed','super_speed'), array('USB 1.0','USB 1.1','USB 2.0','USB 3.x'), $device['device_speed']);
			} else {
				$device['device_speed'] = 'USB 1.1';
			}
            
            // Set device types
			if (stripos($device['name'], 'iSight') !== false) {
				$device['type'] = 'Camera';
			}
			else if (stripos($device['name'], 'Hub') !== false) {
				$device['type'] = 'USB Hub';
			}
			else if (stripos($device['name'], 'Keyboard') !== false) {
				$device['type'] = 'Keyboard';
			}
			else if (stripos($device['name'], 'IR Receiver') !== false) {
				$device['type'] = 'IR Receiver';
			}
			else if (stripos($device['name'], 'Bluetooth') !== false) {
				$device['type'] = 'Bluetooth Controller';
			}
			else if (stripos($device['name'], 'iPhone') !== false) {
				$device['type'] = 'iPhone';
			}
			else if (stripos($device['name'], 'iPad') !== false) {
				$device['type'] = 'iPad';
			}
			else if (stripos($device['name'], 'iPod') !== false) {
				$device['type'] = 'iPod';
			}
			else if (stripos($device['name'], 'Mouse') !== false) {
				$device['type'] = 'Mouse';
			}
			else if (stripos($device['name'], 'Card Reader') !== false) {
				$device['type'] = 'Mass Storage';
			}
			else if (stripos($device['manufacturer'], 'DisplayLink') !== false) {
				$device['type'] = 'Display'; // Set by manufacturer
			}
			else if (stripos($device['name'], 'UPS') !== false) {
				$device['type'] = 'UPS';
			}
			else if (stripos($device['name'], 'audio') !== false) {
				$device['type'] = 'Audio Device';
			}
			else if (stripos($device['name'], 'Camera') !== false) {
				$device['type'] = 'Camera';
			}
			else if (stripos($device['name'], 'Video') !== false) {
				$device['type'] = 'Camera';
			}
			else if (stripos($device['name'], 'Display') !== false) {
				$device['type'] = 'Display';
			}

            
            // Check for Mass Storage
            if ($device['media'] == 1 ) {
				$device['type'] = 'Mass Storage';
			}
                        
            // Adjust Apple vendor ID
            if ($device['vendor_id'] == 'apple_vendor_id' ) {
				$device['vendor_id'] = '0x05ac (Apple, Inc.)';
			}
            
            // Override Internal T/F based on name
            if (stripos($device['name'], 'Internal') !== false) {
				$device['internal'] = 1;
			}
            
			// Set manufacturer from vendor ID if it's blank
			if (! array_key_exists("manufacturer",$device)) {
				preg_match('/\((.*?)\)/s', $device['vendor_id'], $manufactureroutput);
				$device['manufacturer'] = $manufactureroutput[1];
			}
            
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
