<?php
class Certificate_model extends Model {
	
	function __construct($serial='')
	{
		parent::__construct('id', 'certificate'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['cert_exp_time'] = 0; // Unix timestamp of expiration time
		$this->rs['cert_path'] = ''; // Path to certificate
		$this->rs['cert_cn'] = ''; // Common name
		$this->rs['timestamp'] = 0; // Timestamp of last update
		
		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
		
		//indexes to optimize queries
		$this->idx[] = array('serial_number');
		$this->idx[] = array('cert_exp_time');
		$this->idx[] = array('timestamp');
		
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
		// Delete previous set
		$this->delete_where('serial_number=?', $this->serial_number);

		// Parse log data
		$start = ''; // Start date
        foreach(explode("\n", $data) as $line)
        {
        	if($line)
        	{
	        	$parts = explode("\t", $line);

	        	if(count($parts) !== 3)
	        	{
	        		echo 'Invalid log entry: '.$line;
	        	}
	        	else
	        	{
	        		// Convert unix timestamp string to int
	        		$this->cert_exp_time = intval($parts[0]);
	        		// Trim path to 255 chars
	        		$this->cert_path = substr($parts[1], 0, 254);
	        		// Get common name out of subject
	        		if(preg_match('/subject= CN = ([^,]+)/', $parts[2], $matches))
	        		{
	        			$this->cert_cn = $matches[1];
	        		}
	        		else
	        		{
	        			$this->cert_cn = 'Unknown';
	        		}

	        		$this->id = '';
	        		$this->timestamp = time();
		        	$this->create();

	        	}
        	}
        }       		
	} // end process()
}
