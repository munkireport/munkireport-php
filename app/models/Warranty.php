<?php
class Warranty extends Model {
	
	protected $error = '';
	
	function __construct($serial='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['purchase_date'] = '';
		$this->rs['end_date'] = '';
		$this->rs['status'] = '';
		
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_one('serial_number=?', $serial);
			$this->check_status();
		}
		
		$this->serial_number = $serial;
		  
	}
	
	function check_status($force = FALSE)
	{
		// Check if record exists
		if( ! $force && $this->id)
		{
			return $this;
		}

		// Load warranty helper
		require_once(conf('application_path').'helpers/warranty_helper.php');
		
		// Update needed, check with apple
		check_warranty_status($this);
		
		$this->save();
		
		return $this;
	}
	
	
	
}