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
		$this->rs['destinations'] = 0; // Number of configured destinations
		
		
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
		$this->idx[] = array('destinations');
		
		// Create table if it does not exist
		$this->create_table();
				
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
        'TM_NAME: ' => 'location_name',
        'TM_DESTINATIONS: ' => 'destinations');

		// Delete previous entries
		$serial_number = $this->serial_number;
		$this->delete_where('serial_number=?', $serial_number);

        //clear any previous data we had
		foreach($translate as $search => $field) {
			$this->$field = '';
		}

		// Parse log data
		$start = ''; // Start date
		$disk_backup = False; // boolean for disk backup
		$network_backup = False; //boolean for network backup
		
        foreach(explode("\n", $data) as $line)
        {
        	$date = substr($line, 0, 19);
        	$message = substr($line, 21);

        	if( preg_match('/^Attempting to soft mount network destination URL/', $message))
        	{
        		$network_backup = True;
        	}

        	if( preg_match('/^Backing up to \/dev/', $message))
        	{
        		$disk_backup = True;
        	}
        	
        	if( preg_match('/^Starting (automatic|manual) backup/', $message))
        	{
        		$start = $date;
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
        		
        	}
        	
        	if( preg_match('/^Backup failed/', $message))
        	{
        		$this->last_failure = $date;
        		$this->last_failure_msg = $message;
        	}

			foreach($translate as $search => $field) 
			{	    
          	   if((strpos($line, '---------') === 0) && ($this->kind)) 
          	   {
					if(($this->kind == 'Network') && ($network_backup)) 
					{
						$this->last_success = $date;
						//get a new id
						$this->id = 0;
						$this->save(); //the actual save
						break;
					}
					elseif(($this->kind == 'Local') && ($disk_backup)) 
					{
						$this->last_success = $date;
						//get a new id
						$this->id = 0;
						$this->save(); //the actual save
						break;
					}
				    break;
				}    
				elseif(strpos($line, $search) === 0) 
				{ //else if not separator and matches
            		$value = substr($line, strlen($search)); //get the current value
					$this->$field = $value;
					break;
			    }
			    
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
