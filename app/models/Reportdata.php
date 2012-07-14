<?php

class Reportdata extends Model {
    
    function __construct($serial='')
    {
        parent::__construct('id','reportdata'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial'] = ''; $this->rt['serial'] = 'VARCHAR(255) UNIQUE';
		$this->rs['console_user'] = '';
		$this->rs['long_username'] = '';
		$this->rs['remote_ip'] = '';
		$this->rs['runtype'] = '';
		$this->rs['runstate'] = '';
		$this->rs['timestamp'] = time();
				
		// Create table if it does not exist
        $this->create_table();
        
        if($serial)
        {
            $this->retrieve_one('serial=?', $serial);
            $this->serial = $serial;
        }
        
        return $this;
    }

	function process($plist)
	{
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();
		$this->remote_ip = $_SERVER['REMOTE_ADDR'];
		$this->merge($mylist)->save();
	}
}
