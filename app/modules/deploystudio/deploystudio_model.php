<?php
class Deploystudio_model extends Model {
	
	protected $error = '';
	protected $module_dir;
	
	function __construct($serial='')
	{
		parent::__construct('id', 'deploystudio'); //primary key, tablename
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
        
		// Add indexes
		$this->idx[] = array('dstudio-host-serial-number');
		$this->idx[] = array('dstudio-hostname');
		$this->idx[] = array('dstudio-mac-addr');
		$this->idx[] = array('dstudio-last-workflow');
		$this->idx[] = array('cn');
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_record($serial);
		}
		
		$this->serial_number = $serial;
		
		$this->module_dir = dirname(__FILE__);
		  
	}

	 /**
	 * Get DeployStudio data
	 *
	 * @return void
	 * @author tuxudo (John Eberle)
	 **/
	function run_deploystudio_stats()
	{		
        // Check if we should enable DeployStudio lookup
        if (conf('deploystudio_enable'))
            {
				
                // Load deploystudio helper
                require_once($this->module_dir.'/lib/deploystudio_helper.php');
				$ds_helper = new munkireport\module\deploystudio\Deploystudio_helper;
                $ds_helper->pull_deploystudio_data($this);

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
		$this->run_deploystudio_stats();
	}		
}
