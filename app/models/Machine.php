<?php
class Machine extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['hostname'] = '';
		$this->rs['machine_model'] = '';
		$this->rs['machine_desc'] = '';
		$this->rs['img_url'] = '';
		$this->rs['current_processor_speed'] = '';
		$this->rs['cpu_arch'] = '';
		$this->rs['os_version'] = '';
		$this->rs['physical_memory'] = '';
		$this->rs['platform_UUID'] = '';
		$this->rs['number_processors'] = 0;
		$this->rs['SMC_version_system'] = '';
		$this->rs['boot_rom_version'] = '';
		$this->rs['bus_speed'] = '';
		$this->rs['computer_name'] = '';
		$this->rs['l2_cache'] = '';
		$this->rs['machine_name'] = '';
		$this->rs['packages'] = '';	   
		
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
		echo "Machine: got data\n";
		
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
		
		$this->timestamp = time();
		$this->merge($mylist)->save();
	}

	
}