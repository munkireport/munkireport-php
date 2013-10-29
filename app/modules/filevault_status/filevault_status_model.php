<?php
class Filevault_status_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'filevault_status'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['filevault_status'] = '';		   
		
		// Add index
		$this->idx[] = array('filevault_status');

		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
			$this->retrieve_one('serial_number=?', $serial);
		
		$this->serial = $serial;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author abn290
	 **/
	function process($text)
	{		
		$this->filevault_status = $text;
		$this->save();
	}

	
}