<?php
class Warranty extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['sn'] = $serial; $this->rt['sn'] = 'VARCHAR(255) UNIQUE';
		$this->rs['COV_END_DATE'] = '';
		$this->rs['HW_COVERAGE_DESC'] = '';
		$this->rs['PROD_IMAGE_URL'] = ''; // Todo: move to machine
		$this->rs['PROD_DESCR'] = '';
		$this->rs['nextcheck'] = 0;
		
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_one('sn=?', $serial);
			$this->check_status();
		}
			
		
		$this->sn = $serial;
		  
	}
	
	function check_status($force = FALSE)
	{
		// Check if record exists and not older than 30 days
		if( ! $force && $this->id && $this->nextcheck > time()) //Todo: make exp time config item
		{
			return $this;
		}
		
		// Update needed, check with apple
		$url = 'https://selfsolve.apple.com/warrantyChecker.do?sn='.$this->sn;

		$json = file_get_contents($url);
		$json = substr($json, 5, -1);
		$json_obj = json_decode($json);

		if(isset($json_obj->ERROR_CODE))
		{
			return $json_obj->ERROR_DESC;
		}
		else
		{
			$this->nextcheck = time() + 60 * 60 * 24 * 30;//Todo: make exp time config item
			$this->merge((array)$json_obj)->save();
		}
		return $this;
	}
	
}