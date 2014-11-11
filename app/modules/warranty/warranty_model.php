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
		
		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
		
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

		alert("warranty: current status: $this->status");

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
			case 'Lookup failed':
				break;
			case 'Virtual Machine':
				// Don't check
				return;
			default:
				// Unknown status
				alert('warranty: unknown status: '.$this->status, 'warning');

		}
		$this->check_status($force = TRUE);

		alert(sprintf("warranty: new status: %s", $this->status));
	}

	
	function check_status($force = FALSE)
	{
		// Check if record exists
		if( ! $force && $this->id)
		{
			return $this;
		}

		// Store previous status
		$prev_status = $this->status;

		// Load warranty helper
		require_once(conf('application_path').'helpers/warranty_helper.php');
		
		// Update needed, check with apple
		$error = check_warranty_status($this);

		// If error and previous status was set, don't save
		// This happens when Apple's servers are in maintenance
		if($error && $prev_status)
		{
			alert("warranty: update warranty status failed ($error)", 'warning');
			$this->retrieve($this->id);
		}
		else
		{
			$this->save();
		}
		
		return $this;
	}
		
}