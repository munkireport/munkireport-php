<?php

class Reportdata_model extends Model {
    
    function __construct($serial='')
    {
        parent::__construct('id','reportdata'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = ''; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['console_user'] = '';
		$this->rs['long_username'] = '';
		$this->rs['remote_ip'] = '';
		$this->rs['uptime'] = 0; $this->rt['uptime'] = 'INTEGER DEFAULT 0';// Uptime in seconds
		$this->rs['reg_timestamp'] = time(); // Registration date
		$this->rs['machine_group'] = 0; $this->rt['machine_group'] = 'INT DEFAULT 0';
		$this->rs['timestamp'] = time();

		// Schema version, increment when creating a db migration
		$this->schema_version = 3;


		// Create indexes
        $this->idx[] = array('console_user');
        $this->idx[] = array('long_username');
        $this->idx[] = array('remote_ip');
        $this->idx[] = array('reg_timestamp');
        $this->idx[] = array('timestamp');
		$this->idx[] = array('machine_group');	

				
		// Create table if it does not exist
        $this->create_table();
        
        if($serial)
        {
            $this->retrieve_record($serial);
            $this->serial_number = $serial;
        }
        
        return $this;
    }

	/**
	 * Register IP and time
	 *
	 * @return object this
	 * @author AvB
	 **/
	function register()
	{
		// Test for proxy
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			$this->remote_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else
		{
			$this->remote_ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$this->timestamp = time();

		return $this;
	}

	/**
	 * Get machine_groups
	 *
	 * @return array machine_groups
	 * @author AvB
	 **/
	function get_groups($count = FALSE)
	{
		if($count)
		{
			$out = array();
		}
		else
		{
			$out = array(0 => 0);
		}
		
		$sql = "SELECT machine_group, COUNT(*) as cnt FROM reportdata GROUP BY machine_group";
		foreach($this->query($sql) AS $obj)
		{
			if($count)
			{
				$obj->machine_group = intval($obj->machine_group);
				$obj->cnt = intval($obj->cnt);
				$out[] = $obj;
			}
			else
			{
				$out[$obj->machine_group] = $obj->machine_group;
			}
			
		}

		return $out;
	}

	function process($plist)
	{
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();
		
		// Remove serial_number from mylist, use the cleaned serial that was provided in the constructor.
		unset($mylist['serial_number']);

		// If console_user is empty, retain previous entry
		if( ! $mylist['console_user'])
		{
			unset($mylist['console_user']);
		}

		// If long_username is empty, retain previous entry
		if( array_key_exists('long_username', $mylist) && empty($mylist['long_username']))
		{
			unset($mylist['long_username']);
		}
		
		$this->merge($mylist)->register()->save();
	}

}
