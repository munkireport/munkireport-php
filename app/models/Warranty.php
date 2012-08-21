<?php
class Warranty extends Model {
	
	protected $error = '';
	
	function __construct($serial='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['sn'] = $serial; $this->rt['sn'] = 'VARCHAR(255) UNIQUE';
		$this->rs['end_date'] = '';
		$this->rs['status'] = '';
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
		
		// Unfortunately we have to scrape the page as Apple discontinued the json api
		$url = 'https://selfsolve.apple.com/wcResults.do';
		$data = array ('sn' => $this->sn, 'num' => '0');
		$data = http_build_query($data);

		$context_options = array (
		        'http' => array (
		            'method' => 'POST',
		            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
		                . "Content-Length: " . strlen($data) . "\r\n",
		            'content' => $data
		            )
		        );

		$context = stream_context_create($context_options);
		$result = file_get_contents($url, FALSE, $context);
		
		
		if(preg_match('/invalidserialnumber/', $result))
		{
			// Check invalid serial
			$this->status = 'Invalid serial number';
		}
		elseif(preg_match("/window.location.href='\/RegisterProduct.do\?productRegister=Y/", $result))
		{
			// Check registration
			$this->status = 'Unregistered serialnumber';
		}
		elseif(preg_match('/warrantyPage.warrantycheck.displayHWSupportInfo\(false/', $result))
		{
			// Get expired status
			$this->status = 'Expired';
			$this->end_date = '0000-00-00';
		}
		elseif(preg_match('/warrantyPage.warrantycheck.displayHWSupportInfo\(true/', $result))
		{
			// Get support status
			$this->status = 'Supported';
			
			// Get estimated exp date
			if(preg_match('/Estimated Expiration Date: ([^<]+)</', $result, $matches))
			{
				$this->end_date = date('Y-m-d', strtotime($matches[1]));
			}	
		}
		else
		{
			$this->status = 'No information found';
		}
		
		// Get info
		if(preg_match("/warrantyPage.warrantycheck.displayProductInfo\('([^\']+)', '([^\']+)'/", $result, $matches))
		{
			// Save img_url
			$machine = new Machine($this->sn);
			$machine->img_url = $matches[1];
			$machine->machine_desc = $matches[2];
			$machine->save();
		}
		
		
		$this->nextcheck = time() + 60 * 60 * 24 * 30;//Todo: make exp time config item
		$this->save();
		
		return $this;
	}
	
	
	
}