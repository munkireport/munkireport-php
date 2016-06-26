<?php
class Munkireport_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'munkireport'); //primary key, tablename
		$this->rs['id'] = 0;
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['runtype'] = '';
		$this->rs['version'] = '';
		$this->rs['errors'] = 0;
		$this->rs['warnings'] = 0;
		$this->rs['manifestname'] = '';
		$this->rs['error_json'] = ''; $this->rt['errors'] = 'BLOB'; // JSON object errors
		$this->rs['warning_json'] = ''; $this->rt['warnings'] = 'BLOB'; // JSON object with warnings
		$this->rs['starttime'] = '';
		$this->rs['endtime'] = '';
		$this->rs['timestamp'] = '';


		// Add indexes
		$this->idx[] = array('timestamp');
		$this->idx[] = array('runtype');
		$this->idx[] = array('version');
		$this->idx[] = array('manifestname');
		$this->idx[] = array('errors');
		$this->idx[] = array('warnings');

		// Schema version, increment when creating a db migration
		$this->schema_version = 4;

		// Create table if it does not exist
        $this->create_table();
        
		if ($serial)
		{
		    $this->retrieve_record($serial);
            if ( ! $this->rs['serial_number'])
            {
                $this->serial = $serial;
            }
		}

	}
	
	/**
	 * Get manifests statistics
	 *
	 *
	 **/
	public function get_manifest_stats()
	{
		$out = array();
		$filter = get_machine_group_filter();
		$sql = "SELECT COUNT(1) AS count, manifestname 
			FROM munkireport
			LEFT JOIN reportdata USING (serial_number)
			$filter
			GROUP BY manifestname
			ORDER BY count DESC";
			
		foreach($this->query($sql) as $obj)
		{
			$obj->manifestname = $obj->manifestname ? $obj->manifestname : 'Unknown';
			$out[] = $obj;
		}
		
		return $out;
	}
	
	/**
	 * Get munki versions
	 *
	 *
	 **/
	public function get_versions()
	{
		$filter = get_machine_group_filter();
		$sql = "SELECT version, COUNT(1) AS count
				FROM munkireport
				LEFT JOIN reportdata USING (serial_number)
				$filter
				GROUP BY version
				ORDER BY COUNT DESC";
		return $this->query($sql);
	}
	
	/**
	 * Get machines with pending installs
	 *
	 *
	 * @param int $hours Amount of hours to look back in history
	 **/
	public function get_pending($hours=24)
	{
		$timestamp = date('Y-m-d H:i:s', time() - 60 * 60 * $hours);
		$out = array();
		$filter = get_machine_group_filter('AND');
		$sql = "SELECT computer_name, pendinginstalls, reportdata.serial_number
		    FROM reportdata
		    LEFT JOIN munkireport USING(serial_number)
		    LEFT JOIN machine USING(serial_number)
		    WHERE pendinginstalls > 0
		    $filter
			AND munkireport.timestamp > '$timestamp'
		    ORDER BY pendinginstalls DESC";
		
		return $this->query($sql);
	}
	
	/**
	 * Get pending installs
	 *
	 *
	 * @param int $hours Amount of hours to look back in history
	 **/
	public function get_pending_installs($hours=24)
	{
		$fromdate = date('Y-m-d H:i:s', time() - 3600 * $hours);
		$updates_array = array();
		$filter = get_machine_group_filter('AND');
		$sql = "SELECT m.serial_number, report_plist 
				FROM munkireport m
				LEFT JOIN reportdata USING (serial_number)
				WHERE pendinginstalls > 0
				$filter
				AND m.timestamp > '$fromdate'";
		return $this->query($sql);
	}
	
	/**
	 * Get statistics
	 *
	 * Get object describing statistics
	 *
	 * @param integer hours hours of statistics
	 **/
	public function get_stats($hours = 24)
	{
		$timestamp = date('Y-m-d H:i:s', time() - 60 * 60 * $hours);
		$sql = "SELECT 
			SUM(errors > 0) as error, 
			SUM(warnings > 0) as warning
			FROM munkireport
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter()."
			AND munkireport.timestamp > '$timestamp'";

		return current($this->query($sql));
	}

	
	function process($plist)
	{		
		$this->timestamp = date('Y-m-d H:i:s');
		
		if ( ! $plist){
            throw new Exception("Error Processing Request: No property list found", 1);
		}
				
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();
		
		// Translate plist keys to db keys
		$translate = array(
			'ManagedInstallVersion' => 'version',
			'ManifestName' => 'manifestname',
			'RunType' => 'runtype',
			'StartTime' => 'starttime',
			'EndTime' => 'endtime',
		);
		
		foreach ($translate as $key => $dbkey) {
			if(array_key_exists($key, $mylist)){
				$this->$dbkey = $mylist[$key];
			}
		}
		
		// Parse errors and warnings
		$errorsWarnings = array('Errors' => 'error_json', 'Warnings' => 'warning_json');
		foreach ($errorsWarnings as $key => $json) {
			$dbkey = strtolower($key);
			if(isset($mylist[$key]) && is_array($mylist[$key])){
				// Store count
				$this->$dbkey = count($mylist[$key]);
				
				// Store json
				$this->$json = json_encode($mylist[$key]);
			}
			else{
				// reset
				$this->$dbkey = 0;
				$this->$json = json_encode(array());
			}
		}
		
		// Store record	
		$this->save();
		
		// Store apropriate event:
		if($this->rs['errors'] == 1) // Errors is a protected name
		{
			$this->store_event(
				'danger',
				'munki.error',
				json_encode(array('error' => truncate_string($mylist['Errors'][0])))
			);
		}
		elseif($this->rs['errors'] > 1) // Errors is a protected name
		{
			$this->store_event(
				'danger',
				'munki.error',
				json_encode(array('count' => $this->rs['errors']))
			);
		}
		elseif($this->warnings == 1)
		{
			$this->store_event(
				'warning',
				'munki.warning',
				json_encode(array('warning' => truncate_string($mylist['Warnings'][0])))
			);
		}
		elseif($this->warnings > 1)
		{
			$this->store_event(
				'warning',
				'munki.warning',
				json_encode(array('count' => $this->warnings))
			);
		}
		else
		{
			// Delete event
			$this->delete_event();
		}
		
		return $this;
	}	
}