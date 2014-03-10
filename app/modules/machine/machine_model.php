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
		$this->rs['os_version'] = '';
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


		// Schema version, increment when creating a db migration
		$this->schema_version = 3;

		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
			$this->retrieve_one('serial_number=?', $serial);
		
		$this->serial = $serial;
		  
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
		
		$this->timestamp = time();
		$this->merge($mylist)->save();
	}

	
}