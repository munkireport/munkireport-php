<?php

namespace munkireport;

/**
 * Utility for legacy bluetooth support
 *
 * Converts data from clients with the old Bluetooth module
 * to the new format
 *
 * 
 */
class Bt_legacy_support {
	
	private $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	function toArray()
	{
		$out = array();
		
		// Translate network strings to db fields
		$translate = array(
			'Status = ' => 'bluetooth_power',
			'Keyboard = ' => 'apple wireless keyboard',
			'Mouse = ' => 'apple magic mouse',
			'Trackpad = ' => 'apple wireless trackpad');
			
		//clear any previous data we had
		foreach($translate as $search => $field) {
			$this->$field = -1;
		}
		// Parse data
		foreach(explode("\n", $this->data) as $line) {
			// Translate standard entries
			foreach($translate as $search => $field) {

				if(strpos($line, $search) === 0) {

					$value = trim(substr($line, strlen($search)));
					
					// Legacy client module
					if($value == 'Disconnected')
					{
						// Don't report on disconnected devices
						continue;
					}
					elseif(preg_match('/(\d+)% battery life remaining/', $value, $matches))
					{
						$value = $matches[1];
					}
					elseif(preg_match('/Bluetooth is (.+)/', $value, $matches))
					{
						$value = $matches[1] == 'on' ? 1 : 0;
					}
					
					if($value > -1){
						$out[$field] = intval($value);
					}
					break;
				}
			}

		} //end foreach explode lines
		
		return $out;
	}
}