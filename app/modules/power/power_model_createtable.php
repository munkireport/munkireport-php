<?php
class Power_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'power'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['manufacture_date'] = '';
		$this->rs['design_capacity'] = 0;
		$this->rs['max_capacity'] = 0;
		$this->rs['max_percent'] = 0;
		$this->rs['current_capacity'] = 0;	   
		$this->rs['current_percent'] = 0;	   
		$this->rs['cycle_count'] = 0;	   
		$this->rs['temperature'] = '';	   
		$this->rs['condition'] = '';	   

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
		
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
	 * 
	 **/
	function process($data)
	{		
		// Translate network strings to db fields
        $translate = array(
        	'manufacture_date = ' => 'manufacture_date',
        	'design_capacity = ' => 'design_capacity',
        	'max_capacity = ' => 'max_capacity',
        	'max_percent = ' => 'max_percent',
        	'current_capacity = ' => 'current_capacity',
        	'current_percent = ' => 'current_percent',
        	'cycle_count = ' => 'cycle_count',
        	'temperature = ' => 'temperature',
        	'condition = ' => 'condition');

//clear any previous data we had
		foreach($translate as $search => $field) {
			$this->$field = '';
		}
		// Parse data
		foreach(explode("\n", $data) as $line) {
		    // Translate standard entries
			foreach($translate as $search => $field) {
			    
			    if(strpos($line, $search) === 0) {
				    
				    $value = substr($line, strlen($search));
				    
				    $this->$field = $value;
				    break;
			    }
			} 
		    
		} //end foreach explode lines
		$this->save();
	}
}