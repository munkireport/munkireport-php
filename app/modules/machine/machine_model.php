<?php
class Machine_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'machine'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['hostname'] = '';
		$this->rs['machine_model'] = '';
		$this->rs['machine_desc'] = '';
		$this->rs['img_url'] = '';
		$this->rs['cpu'] = '';
		$this->rs['current_processor_speed'] = '';
		$this->rs['cpu_arch'] = '';
		$this->rs['os_version'] = 0;
		$this->rs['physical_memory'] = 0;
		$this->rs['platform_UUID'] = '';
		$this->rs['number_processors'] = 0;
		$this->rs['SMC_version_system'] = '';
		$this->rs['boot_rom_version'] = '';
		$this->rs['bus_speed'] = '';
		$this->rs['computer_name'] = '';
		$this->rs['l2_cache'] = '';
		$this->rs['machine_name'] = '';
		$this->rs['packages'] = '';
		$this->rs['buildversion'] = '';

		// Add indexes
		$this->idx['hostname'] = array('hostname');
		$this->idx['machine_model'] = array('machine_model');
		$this->idx['machine_desc'] = array('machine_desc');
		$this->idx['cpu'] = array('cpu');
		$this->idx['current_processor_speed'] = array('current_processor_speed');
		$this->idx['cpu_arch'] = array('cpu_arch');
		$this->idx['os_version'] = array('os_version');
		$this->idx['physical_memory'] = array('physical_memory');
		$this->idx['platform_UUID'] = array('platform_UUID');
		$this->idx['number_processors'] = array('number_processors');
		$this->idx['SMC_version_system'] = array('SMC_version_system');
		$this->idx['boot_rom_version'] = array('boot_rom_version');
		$this->idx['bus_speed'] = array('bus_speed');
		$this->idx['computer_name'] = array('computer_name');
		$this->idx['l2_cache'] = array('l2_cache');
		$this->idx['machine_name'] = array('machine_name');
		$this->idx['packages'] = array('packages');
		$this->idx[] = array('buildversion');


		// Schema version, increment when creating a db migration
		$this->schema_version = 5;

		// Create table if it does not exist
		$this->create_table();

		if ($serial)
			$this->retrieve_record($serial);

		$this->serial = $serial;

	}
	
	/**
	 * Get duplicate computernames
	 *
	 *
	 **/
	public function get_duplicate_computernames()
	{
		$out = array();
		$filter = get_machine_group_filter();
		$sql = "SELECT computer_name,
				COUNT(*) AS count
				FROM machine
				LEFT JOIN reportdata USING (serial_number)
				$filter
				GROUP BY computer_name
				HAVING count > 1
				ORDER BY count DESC";
				
		foreach($this->query($sql) as $obj)
		{
			$out[] = $obj;
		}
		
		return $out;
	
	}
	
	/**
	 * Get model statistics
	 *
	 **/
	public function get_model_stats()
	{
		$out = array();
		$machine = new Machine_model();
		$filter = get_machine_group_filter();
		$sql = "SELECT count(*) AS count, machine_desc 
			FROM machine
			LEFT JOIN reportdata USING (serial_number)
			$filter
			GROUP BY machine_desc 
			ORDER BY count DESC";
		
		foreach($this->query($sql) as $obj)
		{
			$obj->machine_desc = $obj->machine_desc ? $obj->machine_desc : 'Unknown';
			$out[] = $obj;
		}
		
		return $out;

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

		// Remove serial_number from mylist, use the cleaned serial that was provided in the constructor.
		unset($mylist['serial_number']);

		// Set default computer_name
		if( ! isset($mylist['computer_name']) OR trim($mylist['computer_name']) == '')
		{
			$mylist['computer_name'] = 'No name';
		}

		// Convert memory string (4 GB) to int
		if( isset($mylist['physical_memory']))
		{
			$mylist['physical_memory'] = intval($mylist['physical_memory']);
		}

		// Convert OS version to int
		if( isset($mylist['os_version']))
		{
			$digits = explode('.', $mylist['os_version']);
			$mult = 10000;
			$mylist['os_version'] = 0;
			foreach($digits as $digit)
			{
				$mylist['os_version'] += $digit * $mult;
				$mult = $mult / 100;
			}
		}
		
		// Dirify buildversion
		if( isset($mylist['buildversion']))
		{
			$mylist['buildversion'] = preg_replace('/[^A-Za-z0-9]/', '', $mylist['buildversion']);
		}

		$this->timestamp = time();
		$this->merge($mylist)->save();
	}


}
