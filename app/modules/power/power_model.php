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
		$this->rs['temperature'] = 0;	   
		$this->rs['condition'] = '';	   
		$this->rs['timestamp'] = 0; // Unix time when the report was uploaded

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
        	'current_capacity = ' => 'current_capacity',
        	'cycle_count = ' => 'cycle_count',
        	'temperature = ' => 'temperature',
        	'condition = ' => 'condition');

		// Reset values
		$this->manufacture_date = '';
		$this->design_capacity = 0;
		$this->max_capacity = 0;
		$this->max_percent = 0;
		$this->current_capacity = 0;
		$this->current_percent = 0;
		$this->cycle_count = 0;
		$this->temperature = 0;
		$this->condition = '';
		$this->timestamp = 0;

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


		// Calculate maximum capacity as percentage of original capacity
		$this->max_percent = round(($this->max_capacity / $this->design_capacity * 100 ), 0 );


		// Calculate percentage of current maximum capacity
		$this->current_percent = round(($this->current_capacity / $this->max_capacity * 100 ), 0 );


		// Convert battery manufacture date to calendar date.
			$ManufactureDate = $this->manufacture_date;
			$year = (int) (1980 + ( $ManufactureDate / 512 ));
			$YearNumber = (int) (( $year - 1980 ) * 512 );

			$month = 1;
			while($month <= 12) {
				$day = (int) (( $ManufactureDate - $YearNumber ) - ( $month * 32 ));
				if( $day >=1 && $day <=31 ) {
					$day = sprintf("%02d", $day);	// pad with leading zero if required
					$month = sprintf("%02d", $month);
					$ManufactureDate = "$year-$month-$day";
				}
			$month++;
			}
		$this->manufacture_date = $ManufactureDate;


		//timestamp added by the server
		$this->timestamp = time();

		    
		} //end foreach explode lines
		$this->save();

 
	}
}