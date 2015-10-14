<?php
class Crashplan_model extends Model {
	
	function __construct($serial='')
	{
		parent::__construct('id', 'crashplan'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['destination'] = ''; // Name of destination
		$this->rs['last_success'] = 0; // Timestamp of last successful backup
		$this->rs['duration'] = 0; // duration in seconds
		$this->rs['last_failure'] = 0; // Timestamp of last failed backup
		$this->rs['reason'] = ''; // Reason of last failure
		$this->rs['timestamp'] = 0; // Timestamp of last update
		
		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
		
		//indexes to optimize queries
		$this->idx[] = array('serial_number');
		$this->idx[] = array('reason');
		
		// Create table if it does not exist
		$this->create_table();
				  
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
		// Delete previous entries
		$serial_number = $this->serial_number;
		$this->delete_where('serial_number=?', $serial_number);

		// Parse data
		$lines = explode("\n", $data);
		$headers =  str_getcsv(array_shift($lines));
		foreach($lines as $line)
		{
			if($line)
			{
				$this->merge(array_combine($headers, str_getcsv($line)));
				$this->id = '';
				$this->serial_number = $serial_number;
				$this->timestamp = time();
				$this->save();
			}
		}
		
		throw new Exception("Error Processing Request", 1);
				
	} // end process()
}
