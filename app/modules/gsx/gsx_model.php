<?php
class gsx_model extends Model {
	
	protected $error = '';
	
	function __construct($serial='')
	{
		parent::__construct('id', 'gsx'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['warrantyStatus'] = '';
		$this->rs['coverageEndDate'] = '';
		$this->rs['coverageStartDate'] = '';
		$this->rs['daysRemaining'] = '';
		$this->rs['estimatedPurchaseDate'] = '';
		$this->rs['purchaseCountry'] = '';
		$this->rs['registrationDate'] = '';
		$this->rs['productDescription'] = '';
		$this->rs['configDescription'] = '';
		$this->rs['contractCoverageEndDate'] = '';
		$this->rs['contractCoverageStartDate'] = '';
		$this->rs['contractType'] = '';
		$this->rs['laborCovered'] = '';
		$this->rs['partCovered'] = '';
		$this->rs['warrantyReferenceNo'] = '';
		$this->rs['isLoaner'] = '';
		$this->rs['warrantyMod'] = '';
		$this->rs['isVintage'] = '';
		$this->rs['isObsolete'] = '';


		// Schema version, increment when creating a db migration
		$this->schema_version = 0;
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_record($serial);
			//$this->get_gsx_stats();
		}
		
		$this->serial_number = $serial;
		  
	}
	
	/**
	 * Get GSX statistics
	 *
	 **/
	public function get_stats($alert=FALSE)
	{
		$out = array();
		$filter = get_machine_group_filter();
		$datefilter = '';
		
		// Check if we have to only return machines due in 30 days
		if($alert)
		{
			$thirtydays = date('Y-m-d', strtotime('+30days'));
			$yesterday = date('Y-m-d', strtotime('-1day'));
			$datefilter = "AND (end_date BETWEEN '$yesterday' AND '$thirtydays')";
		}
		$sql = "SELECT count(*) AS count, status
					FROM gsx
					LEFT JOIN reportdata USING (serial_number)
					$filter
					$datefilter
					GROUP BY status
					ORDER BY count DESC";
		
		foreach($this->query($sql) as $obj)
		{
			$out[] = $obj;
		}
		
		return $out;
	}

	/**
	 * Check GSX status and update
	 *
	 * @return void
	 * @author John Eberle
	 **/
	//function get_gsx_stats($force = FALSE)
	function run_gsx_stats()
	{		
        // Check if we should enable GSX lookup
        // Useful for stopping lookups if IP address changes
        if (conf('gsx_enable'))
            {
            
                // Load gsx helper
            require_once(conf('application_path').'helpers/gsx_helper.php');
            
                get_gsx_stats($this);
                // ^^ Comment and uncomment to turn off and on
            }
        
		return $this;
	}

	/**
	 * Process method, is called by the client
	 *
	 * @return void
	 * @author John Eberle
	 **/
	function process()
	{
		$this->run_gsx_stats();

		//require_once(conf('application_path').'helpers/gsx_helper.php');
	}		
}