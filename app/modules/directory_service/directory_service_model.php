<?php
class Directory_service_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'directoryservice'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['which_directory_service'] = '';
		$this->rs['directory_service_comments'] = '';   
		
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
	 * @author gmarnin
	 **/
	function process($data)
	{		
		$dataArray = explode("\n", $data);
		
		if( sizeof($dataArray)  > 2 ) {
			$this->which_directory_service = trim($dataArray[0]);
			$this->directory_service_comments = trim($dataArray[1]);
		} else {
			$this->which_directory_service = trim($dataArray[0]);
			$this->directory_service_comments = "";
		}
		//var_dump($dataArray);
		$this->save();
	}

	
}