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
		
		// Install info - used when there is one install to report on
		$install_info = array();
		
		// Problem installs
		$this->failedinstalls = 0;
		if(array_key_exists('ProblemInstalls', $mylist))
		{
			$this->failedinstalls = count($mylist['ProblemInstalls']);
			if($this->failedinstalls == 1)
			{
				$install_info = array(
					'pkg' => $mylist['ProblemInstalls'][0]['display_name'],
					'reason' => $mylist['ProblemInstalls'][0]['note']
				);
			}
		}
		
		// Calculate pending installs
		$this->pendinginstalls = max(($this->itemstoinstall + $this->appleupdates) - $this->installresults, 0);

		// Calculate pending removals
		$removal_items = isset($mylist['ItemsToRemove']) ? count($mylist['ItemsToRemove']) : 0;
        $this->pendingremovals = max($removal_items - $this->removalresults, 0);

		// Check results for failed installs
		if($this->installresults)
		{
			foreach($mylist['InstallResults'] as $result)
			{
				if($result["status"])
				{
					$this->failedinstalls++;
					$this->installresults--;
					$install_info = array(
						'pkg' => $result['display_name'],
						'reason' => '' // Client should handle default reason
					);
				}
				else {
					$install_info_success = array(
						'pkg' => $result['display_name'] . ' ' .$result['version']
					);
				}
			}
		}

		# Save plist todo: remove all cruft from plist
		$this->report_plist = $mylist;
				
		$this->save();
		
		// Store apropriate event:
		if($this->failedinstalls == 1)
		{
			$this->store_event(
				'danger',
				'pkg_failed_to_install',
				json_encode($install_info)
			);
		}
		elseif($this->failedinstalls > 1)
		{
			$this->store_event(
				'danger',
				'pkg_failed_to_install',
				json_encode(array('count' => $this->failedinstalls))
			);
		}
		elseif($this->errors == 1)
		{
			$this->store_event(
				'danger',
				'munki.error',
				json_encode(array('error' => truncate_string($mylist['Errors'][0])))
			);
		}
		elseif($this->errors > 1)
		{
			$this->store_event(
				'danger',
				'munki.error',
				json_encode(array('count' => $this->errors))
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
		elseif($this->installresults == 1)
		{
			$this->store_event(
				'success',
				'munki.package_installed',
				json_encode($install_info_success)
			);
		}
		elseif($this->installresults > 1)
		{
			$this->store_event(
				'success',
				'munki.package_installed',
				json_encode(array('count' => $this->installresults))
			);
		}
		else
		{
			// Delete event
			$this->delete_event();
		}
		
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