<?php
class Disk_report_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'diskreport'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['TotalSize'] = 0; $this->rt['TotalSize'] = 'BIGINT';
		$this->rs['FreeSpace'] = 0; $this->rt['FreeSpace'] = 'BIGINT';
		$this->rs['Percentage'] = 0;
		$this->rs['SMARTStatus'] = '';
		$this->rs['VolumeType'] = '';
		$this->rs['BusProtocol'] = '';
		$this->rs['Internal'] = 0; // Boolean
		$this->rs['MountPoint'] = ''; 
		$this->rs['VolumeName'] = '';
		$this->rs['CoreStorageEncrypted'] = 0; //Boolean
		$this->rs['timestamp'] = 0;

		$this->idx[] = array('serial_number');
		$this->idx[] = array('VolumeType');
		$this->idx[] = array('MountPoint');
		$this->idx[] = array('VolumeName');

		// Schema version, increment when creating a db migration
		$this->schema_version = 1;
		
		// Create table if it does not exist
		$this->create_table();
				  
	}

	/**
	 * Get statistics
	 *
	 * @return array
	 * @author 
	 **/
	function get_stats($mountpoint = '/')
	{
		$sql = "SELECT COUNT(CASE WHEN FreeSpace > 10737418239 THEN 1 END) AS success,
						COUNT(CASE WHEN FreeSpace < 10737418240 THEN 1 END) AS warning,
						COUNT(CASE WHEN FreeSpace < 5368709120 THEN 1 END) AS danger 
						FROM diskreport
						LEFT JOIN reportdata USING (serial_number)
						WHERE MountPoint = '$mountpoint'
						".get_machine_group_filter('AND');
		return current($this->query($sql));
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author abn290
	 **/
	function process($plist)
	{		

		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();
		if( ! $mylist)
		{
			throw new Exception("No Disks in report", 1);
		}

		// Convert old style reports from not migrated clients
		if(isset($mylist['DeviceIdentifier']))
		{
			$mylist = array($mylist);
		}

		// Delete previous set
		$this->delete_where('serial_number=?', $this->serial_number);

		foreach($mylist AS $disk)
		{
			// Calculate percentage
			if(isset($disk['TotalSize']) && isset($disk['FreeSpace']))
			{
				$disk['Percentage'] = round(($disk['TotalSize'] - $disk['FreeSpace']) /
					max($disk['TotalSize'], 1) * 100);
			}

			// Determine VolumeType
			$disk['VolumeType'] = "hdd";
			if(isset($disk['SolidState']) && $disk['SolidState'] == TRUE)
			{
				$disk['VolumeType'] = "ssd";
			}
			if(isset($disk['CoreStorageCompositeDisk']) && $disk['CoreStorageCompositeDisk'] == TRUE)
			{
				$disk['VolumeType'] = "fusion";
			}
			if(isset($disk['RAIDMaster']) && $disk['RAIDMaster'] == TRUE)
			{
				$disk['VolumeType'] = "raid";
			}

			$this->merge($disk);

			// Typecast Boolean values
			$this->Internal = (int) $this->Internal;
			$this->CoreStorageEncrypted = (int) $this->CoreStorageEncrypted;

			$this->id = '';
			$this->timestamp = time();
			$this->create();
		}
	}
}