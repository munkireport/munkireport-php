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
     * Get USB device names for widget
     *
     **/
     public function get_usb_devices()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN name <> '' AND name IS NOT NULL THEN 1 END) AS count, name 
                FROM usb
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
    
     /**
     * Get USB device types for widget
     *
     **/
     public function get_usb_types()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN type <> '' AND type IS NOT NULL THEN 1 END) AS count, type 
                FROM usb
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY type
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->type = $obj->type ? $obj->type : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }
    
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
			if( ! array_key_exists("name", $device)){
				continue;
			}
            
			// Skip Bus types USB31Bus, USB11Bus, etc.
			if(preg_match('/^USB(\d+)?Bus$/', $device['name']))
			{
				continue;
			}
			
			// Check for USB bus devices and simulated USB devices to exclude
            $excludeusb = array("UHCI Root Hub Simulation","EHCI Root Hub Simulation");
            if (in_array($device['name'], $excludeusb)) {
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
			
			// Make sure manufacturer is set
			$device['manufacturer'] = isset($device['manufacturer']) ? $device['manufacturer'] : '';
            
            // Map name to device type
			$device_types = array(
				'Camera' => array('isight', 'camera', 'video'),
				'USB Hub' => array('hub'),
				'Keyboard' => array('keyboard'),
				'IR Receiver' => array('ir receiver'),
				'Bluetooth Controller' => array('bluetooth'),
				'iPhone' => array('iphone'),
				'iPad' => array('ipad'),
				'iPod' => array('ipod'),
				'Mouse' => array('mouse'),
				'Mass Storage' => array('card reader', 'os x install disk', 'apple usb superdrive'),
				'Display' => array('displaylink', 'display', 'monitor'),
				'Ethernet' => array('ethernet'),
				'Network' => array('network', 'ethernet', 'modem'),
				'UPS' => array('ups'),
				'Audio Device' => array('audio'),
				'TouchBar' => array('ibridge')
			);
			$search = '/.*'.strtolower($device['name']).'.*/';
			foreach($device_types as $type => $list){
				if (preg_grep($search, $list)){
					$device['type'] = $type;
					break;
				}
			}
			
			if (stripos($device['name'], 'iBridge') !== false) {
				$device['name'] = 'TouchBar';
			}

            // Check for Mass Storage
            if ($device['media'] == 1 ) {
				$device['type'] = 'Mass Storage';
			}
                                    
            // Override Internal T/F based on name
            if (stripos($device['name'], 'Internal') !== false) {
				$device['internal'] = 1;
			}
            
            // Adjust Apple vendor ID
            if (array_key_exists('vendor_id',$device)) {
            if ($device['vendor_id'] == 'apple_vendor_id') {
				$device['vendor_id'] = '0x05ac (Apple, Inc.)';
			}
            
			// Set manufacturer from vendor ID if it's blank
			if (! array_key_exists("manufacturer",$device)) {
				preg_match('/\((.*?)\)/s', $device['vendor_id'], $manufactureroutput);
				$device['manufacturer'] = $manufactureroutput[1];
			}
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
