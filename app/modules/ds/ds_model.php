<?php
class Ds_model extends Model {
	
	protected $error = '';
	
	function __construct($serial='')
	{
		parent::__construct('id', 'ds'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['architecture'] = '';
		$this->rs['cn'] = '';
		$this->rs['dstudio-auto-disable'] = '';
		$this->rs['dstudio-auto-reset-workflow'] = '';
		$this->rs['dstudio-auto-started-workflow'] = '';
		$this->rs['dstudio-bootcamp-windows-computer-name'] = '';
		$this->rs['dstudio-disabled'] = '';
		$this->rs['dstudio-group'] = '';
		$this->rs['dstudio-host-ard-field-1'] = '';
		$this->rs['dstudio-host-ard-field-2'] = '';
		$this->rs['dstudio-host-ard-field-3'] = '';
		$this->rs['dstudio-host-ard-field-4'] = '';
		$this->rs['dstudio-host-ard-ignore-empty-fields'] = '';
		$this->rs['dstudio-host-delete-other-locations'] = '';
		$this->rs['dstudio-host-model-identifier'] = '';
		$this->rs['dstudio-host-new-network-location'] = '';
		$this->rs['dstudio-host-primary-key'] = '';
		$this->rs['dstudio-host-serial-number'] = '';
		$this->rs['dstudio-host-type'] = '';
		$this->rs['dstudio-hostname'] = '';
		$this->rs['dstudio-last-workflow'] = '';
		$this->rs['dstudio-last-workflow-duration'] = '';
		$this->rs['dstudio-last-workflow-execution-date'] = '';
		$this->rs['dstudio-last-workflow-status'] = '';
		$this->rs['dstudio-mac-addr'] = '';
		
		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_record($serial);
		}
		
		$this->serial_number = $serial;
		  
	}

	 /**
	 * Get DeployStudio data
	 *
	 * @return void
	 * @author tuxudo (John Eberle)
	 **/
	function run_ds_stats()
	{		
        // Check if we should enable DeployStudio lookup
        if (conf('ds_enable'))
            {

                // Load ds helper
                require_once(conf('application_path').'helpers/ds_helper.php');

                pull_ds_data($this);

                // ^^ Comment and uncomment to turn off and on
            }
        
		return $this;
	}

	/**
	 * Process method, is called by the client
	 *
	 * @return void
	 * @author tuxudo (John Eberle)
	 **/
	function process()
	{
		$this->run_ds_stats();
	}		
}