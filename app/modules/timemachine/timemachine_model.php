<?php
class Timemachine_model extends Model {
	
	function __construct($serial='')
	{
		parent::__construct('id', 'timemachine'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
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
		$this->idx[] = array('last_success');
		$this->idx[] = array('last_failure');
		$this->idx[] = array('timestamp');
		$this->idx[] = array('kind');
		$this->idx[] = array('location_name');
		$this->idx[] = array('backup_location');
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
			$this->retrieve_record($serial);
		
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
		
        $translate = array(
        'TM_KIND: ' => 'kind',
        'TM_LOCATION: ' => 'backup_location',
        'TM_NAME: ' => 'location_name');

        //clear any previous data we had
		foreach($translate as $search => $field) {
			$this->$field = '';
		}

		// Parse log data
		$start = ''; // Start date
        foreach(explode("\n", $data) as $line)
        {
        	$date = substr($line, 0, 19);
        	$message = substr($line, 21);
        	
			foreach($translate as $search => $field) {
			    
			    if(strpos($line, $search) === 0) {
				    
				    $value = substr($line, strlen($search));
				    
				    $this->$field = $value;
				    break;
			    }
              }
              
        	if( preg_match('/^Starting (automatic|manual) backup/', $message))
        	{
        		$start = $date;
        	}
        	elseif( preg_match('/^Backup completed successfully/', $message))
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
        	}
        	elseif( preg_match('/^Backup failed/', $message))
        	{
        		$this->last_failure = $date;
        		$this->last_failure_msg = $message;
        	}
        }
        
        // Only store if there is data
        if($this->last_success OR $this->last_failure )
        {
			$this->timestamp = time();
        	$this->save();
        }
		
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
