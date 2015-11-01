<?php
class Munkireport_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'munkireport'); //primary key, tablename
		$this->rs['id'] = 0;
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['timestamp'] = '';
		$this->rs['runstate'] = 'done';
		$this->rs['runtype'] = '';
		$this->rs['starttime'] = '';
		$this->rs['endtime'] = '';
		$this->rs['version'] = '';
		$this->rs['errors'] = 0;
		$this->rs['warnings'] = 0;
		$this->rs['manifestname'] = '';
		$this->rs['managedinstalls'] = 0; // Total packages
		$this->rs['pendinginstalls'] = 0; // To be installed
		$this->rs['installresults'] = 0; // Installed
		$this->rs['removalresults'] = 0; // Removed
		$this->rs['failedinstalls'] = 0; // Failed to install
		$this->rs['pendingremovals'] = 0; // To be removed
		$this->rs['itemstoinstall'] = 0; // Munki items
		$this->rs['appleupdates'] = 0; // Apple updates
		$this->rs['report_plist'] = array();


		// Add indexes
		$this->idx[] = array('timestamp');
		$this->idx[] = array('runtype');
		$this->idx[] = array('version');
		$this->idx[] = array('errors');
		$this->idx[] = array('warnings');
		$this->idx[] = array('manifestname');
		$this->idx[] = array('managedinstalls');
		$this->idx[] = array('pendinginstalls');
		$this->idx[] = array('installresults');
		$this->idx[] = array('removalresults');
		$this->idx[] = array('failedinstalls');
		$this->idx[] = array('pendingremovals');
		$this->idx[] = array('itemstoinstall');
		$this->idx[] = array('appleupdates');
		
		// Schema version, increment when creating a db migration
		$this->schema_version = 3;

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
	
	function process($plist)
	{		
		$this->timestamp = date('Y-m-d H:i:s');
		
		// Todo: why this check?
		if ( ! $plist)
		{
            $this->errors = 0;
            $this->warnings = 0;
            return $this;
		}
		
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();

		# Check munki version
		if(array_key_exists('ManagedInstallVersion', $mylist))
		{
			$this->version = $mylist['ManagedInstallVersion'];
		}

		# Copy items
		$strings = array('ManifestName', 'RunType', 'RunState', 'StartTime', 'EndTime');
		foreach($strings as $str)
		{
			if(array_key_exists($str, $mylist))
			{
				$lcname = strtolower($str);
				$this->rs[$lcname] = $mylist[$str];
				unset($mylist[$str]);
			}
		}

		// If there's an error downloading the manifest, we don't get a ManagedInstalls
		// array. We retain the old ManagedInstalls array and only store the new
		// Errors, Warnings, StartTime, EndTime
		if( ! array_key_exists('ManagedInstalls', $mylist))
		{
			$strings = array('Errors', 'Warnings');
			foreach($strings as $str)
			{
				$lcname = strtolower($str);
				$this->rs[$lcname] = 0;
				if(array_key_exists($str, $mylist))
				{
					$this->rs[$lcname] = count($mylist[$str]);

					// Store errors and warnings
					$this->rs['report_plist'][$str] = $mylist[$str];
				}
			}

			$this->save();
			return $this;
		}

		# Count items
		$strings = array('Errors', 'Warnings', 'ManagedInstalls', 'InstallResults', 'ItemsToInstall', 'AppleUpdates', 'RemovalResults');
		foreach($strings as $str)
		{
			$lcname = strtolower($str);
			$this->rs[$lcname] = 0;
			if(array_key_exists($str, $mylist))
			{
				$this->rs[$lcname] = count($mylist[$str]);
			}
		}

		// Calculate pending installs
		$this->pendinginstalls = max(($this->itemstoinstall + $this->appleupdates) - $this->installresults, 0);

		// Calculate pending removals
		$removal_items = isset($mylist['ItemsToRemove']) ? count($mylist['ItemsToRemove']) : 0;
        $this->pendingremovals = max($removal_items - $this->removalresults, 0);

		// Check results for failed installs
		$this->failedinstalls = 0;
		if($this->installresults)
		{
			foreach($mylist['InstallResults'] as $result)
			{
				if($result["status"])
				{
					$this->failedinstalls++;
				}
			}
		}

		// Adjust installed items
		$this->installresults -= $this->failed_installs;

		# Save plist todo: remove all cruft from plist
		$this->report_plist = $mylist;
				
		$this->save();
		
		return $this;
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
			SUM(warnings > 0) as warning, 
			SUM(pendinginstalls > 0) as pending,
			SUM(installresults > 0) as installed 
			FROM munkireport
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter()."
			AND munkireport.timestamp > '$timestamp'";

		return current($this->query($sql));
	}
}