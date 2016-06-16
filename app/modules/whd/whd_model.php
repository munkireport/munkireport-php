<?php
class whd_model extends Model {
	
	protected $error = '';
	
	function __construct($serial='')
	{
		parent::__construct('id', 'whd'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['assetNumber'] = '';
		$this->rs['notes'] = ''; $this->rt['notes'] = 'BLOB(65535)'; 
		$this->rs['locationName'] = '';
		$this->rs['modelName'] = '';
		$this->rs['roomName'] = '';
		$this->rs['macAddress'] = '';
		$this->rs['networkAddress'] = '';
		$this->rs['networkName'] = '';
		$this->rs['address'] = '';
		$this->rs['city'] = '';
		$this->rs['locationid'] = '';
		$this->rs['postalCode'] = '';
		$this->rs['state'] = '';
		$this->rs['assetType'] = '';
		$this->rs['client'] = '';
		$this->rs['isReservable'] = '';
		$this->rs['leaseExpirationDate'] = '';
		$this->rs['warrantyExpirationDate'] = '';
		$this->rs['isNotesVisibleToClients'] = '';
		$this->rs['isDeleted'] = '';
		$this->rs['clientEmail'] = '';
		$this->rs['clientName'] = '';
		$this->rs['clientNotes'] = ''; $this->rt['clientNotes'] = 'BLOB(65535)';
		$this->rs['clientPhone'] = '';
		$this->rs['clientPhone2'] = '';
		$this->rs['clientdepartment'] = '';
		$this->rs['clientaddress'] = '';
		$this->rs['clientcity'] = '';
		$this->rs['clientlocationName'] = '';
		$this->rs['clientpostalCode'] = '';
		$this->rs['clientstate'] = '';
		$this->rs['clientroom'] = '';
		$this->rs['clientcompanyName'] = '';
		$this->rs['locaddress'] = '';
		$this->rs['loccity'] = '';
		$this->rs['loccountry'] = '';
		$this->rs['locdomainName'] = '';
		$this->rs['locfax'] = '';
		$this->rs['loclocationName'] = '';
		$this->rs['locnote'] = ''; $this->rt['locnote'] = 'BLOB(65535)';
		$this->rs['locphone'] = '';
		$this->rs['locphone2'] = '';
		$this->rs['locpostalCode'] = '';
		$this->rs['locstate'] = '';
		$this->rs['loccolor'] = '';
		$this->rs['locbusinessZone'] = '';
        
		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
        
		//$this->idx[] = array('serial_number');
		//$this->idx[] = array('assetNumber');
		//$this->idx[] = array('modelName');
		//$this->idx[] = array('locationName');
		//$this->idx[] = array('roomName');
		//$this->idx[] = array('notes');
		//$this->idx[] = array('clientName');
		//$this->idx[] = array('clientEmail');
		//$this->idx[] = array('clientPhone');
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_record($serial);
		}
		
		$this->serial_number = $serial;
		  
	}

    /**
	 * Check whd status and update
	 *
	 * @return void
	 * @author John Eberle (@tuxudo)
	 **/
	function get_whd_status()
	{		
        // Check if we should enable whd lookup
        if (conf('whd_enable'))
            {
            
                // Load whd helper
                require_once(conf('application_path').'helpers/whd_helper.php');
            
                run_whd_status($this);
                // ^^ Comment and uncomment to turn off and on
            }
        
		return $this;
	}
    
	/**
	 * Process method, is called by the client
	 *
	 * @return void
	 * @author John Eberle (@tuxudo)
	 **/
	function process()
	{
		$this->get_whd_status();
	}		
}