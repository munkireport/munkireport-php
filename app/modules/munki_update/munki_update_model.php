<?php
class Munki_update_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'update'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['name'] = '';   
		$this->rs['type'] = '';  
		$this->rs['timestamp'] = 0;  
		
		// Add index
		$this->idx[] = array('name');
		$this->idx[] = array('type');
		$this->idx[] = array('timestamp');

		// Create table if it does not exist
		$this->create_table();
				
		$this->serial = $serial;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author abn290
	 **/
	function process($report_plist)
	{		
		$items = array('ItemsToInstall' => 'pending', 'AppleUpdates' => 'apple');

		// Delete updates for this serial
		
		// Store apple updates and pending installs


	}

	
}