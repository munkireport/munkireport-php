<?php
class Timemachine_model extends Model {
	
	function __construct($serial='')
	{
		parent::__construct('id', 'timemachine'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['last_success'] = ''; // Datetime of last successful backup
		$this->rs['last_failure'] = ''; // Datetime of last failure
		$this->rs['last_failure_msg'] = ''; // Message of the last failure
		$this->rs['duration'] = 0; // Duration in seconds
		$this->rs['timestamp'] = ''; // Timestamp of last update
		$this->rs['kind'] = ''; // Kind of backup (Network, Local)
		$this->rs['location_name'] = ''; // Name of the backup location
		$this->rs['backup_location'] = ''; // Location of the backup
		
		
		// Schema version, increment when creating a db migration
		$this->schema_version = 1;
		
		//indexes to optimize queries
		$this->idx[] = array('serial_number');
		$this->idx[] = array('last_success');
		$this->idx[] = array('last_failure');
		$this->idx[] = array('timestamp');
		$this->idx[] = array('kind');
		$this->idx[] = array('location_name');
		$this->idx[] = array('backup_location');
		
		// Create table if it does not exist
		$this->create_table();
				
		$this->serial = $serial;
		  
	}
	
	// Search for item in string and return value
	function _stringToItem($line, $search)
	{
		if(strpos($line, $search) === 0) 
		{
			return substr($line, strlen($search));
		}
		return FALSE;
	}
	
	// Save data if complete
	function _saveIfComplete()
	{
		//Only store if there is data
		print_r($this->rs);
		if($this->last_success OR $this->last_failure )
		{
			$this->timestamp = time();
			$this->save();
		}

	}
	
	// Reset Data
	// don't reset serial number
	function _resetModel()
	{
		foreach($this->rs as $key => $value)
		{
			if($key == 'serial_number')
			{
				continue;
			}
			
			if(is_int($value))
			{
				$this->rs[$key] = 0;
			}
			else
			{
				$this->rs[$key] = '';
			}
			
		}
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
		
		$translate = array(
			'TM_KIND: ' => 'kind',
			'TM_LOCATION: ' => 'backup_location',
			'TM_NAME: ' => 'location_name'
		);

		// Delete previous entries
		$serial_number = $this->serial_number;
		$this->delete_where('serial_number=?', $serial_number);
		
		// Parse log data
		$inLocation = $locationFound = False;
		$start = ''; // Start date
				
		foreach(explode("\n", $data) as $line)
		{
			// Check if we're in a location block
			$locationFound = $this->_stringToItem($line, 'TM_LOCATION: ');
			if( ! $inLocation && ! $locationFound)
			{
				continue;
			}
						
			// Check if we found another location
			if ($inLocation && $locationFound) {
				$this->_saveIfComplete();
				$this->_resetModel();
			}
			
			$inLocation = True;
			
			$date = substr($line, 0, 19);
			$message = substr($line, 21);

			
			if( preg_match('/^Starting (automatic|manual) backup/', $message))
			{
				$start = $date;
				continue;
			}
			
			if( preg_match('/^Backup completed successfully/', $message))
			{
				if($start)
				{
					$this->duration = strtotime($date) - strtotime($start);
				}
				else
				{
					$this->duration = 0;
				}
				$this->last_success = $date;
				continue;
			}
			
			if( preg_match('/^Backup failed/', $message))
			{
				$this->last_failure = $date;
				$this->last_failure_msg = $message;
				continue;
			}

			foreach($translate as $search => $field) 
			{
				if(strpos($line, $search) === 0) 
				{
					//get the current value
					$this->$field = substr($line, strlen($search));
					break;
				}
			}
		}
		
		$this->_saveIfComplete();
		
		//throw new Exception("Error Processing Request", 1);
		
	}

	/**
	 * Get statistics
	 *
	 * @return void
	 * @author 
	 **/
	function get_stats($hours)
	{
		$now = time();
		$today = date('Y-m-d H:i:s', $now - 3600 * 24);
		$week_ago = date('Y-m-d H:i:s', $now - 3600 * 24 * 7);
		$month_ago = date('Y-m-d H:i:s', $now - 3600 * 24 * 30);
		$sql = "SELECT COUNT(1) as total, 
			COUNT(CASE WHEN last_success > '$today' THEN 1 END) AS today, 
			COUNT(CASE WHEN last_success BETWEEN '$week_ago' AND '$today' THEN 1 END) AS lastweek,
			COUNT(CASE WHEN last_success < '$week_ago' THEN 1 END) AS week_plus
			FROM timemachine
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
		return current($this->query($sql));
	}
}
