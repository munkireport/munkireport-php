<?php
class Warranty_model extends Model {
	
	protected $error = '';
	
	function __construct($serial='')
	{
		parent::__construct('id', 'warranty'); //primary key, tablename
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

	/**
	 * Process method, is called by the client
	 *
	 * @return void
	 * @author 
	 **/
	function process()
	{
		$this->msg(sprintf("Old status: %s", $this->status));

		switch($this->status)
		{
			case 'Supported':
				// If not expired, return;
				if(strtotime($this->rs['end_date']) > time())
				{
					return;
				}
				break;
			case 'No Applecare':
				break;
			case 'Unregistered serialnumber':
				break;
			case 'Expired':
				// Don't check
				return;
			case 'No information found':
				break;
			case 'Virtual Machine':
				// Don't check
				return;
			default:
				// Unknown status
				$this->msg('Unknown status: '.$this->status);

		}
		$this->check_status($force = TRUE);

		$this->msg(sprintf("New status: %s", $this->status));
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
	
	function msg($msg = 'No message', $exit = FALSE)
	{
		echo "Server: warranty: $msg \n";
		$exit && exit;
	}
	
}