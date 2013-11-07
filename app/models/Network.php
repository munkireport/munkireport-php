<?php

class Network extends Model {
    
    function __construct($serial='', $service='')
    {
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial'] = '';
        $this->rs['service'] = ''; // Service name
        $this->rs['status'] = 0; // Active = 1, Inactive = 0
        $this->rs['ethernet'] = ''; // Ethernet address
        $this->rs['ipv4'] = ''; // IPv4 configuration (automatic, manual)
        $this->rs['ipv4ip'] = 0; // IPv4 address as int
        $this->rs['ipv4mask'] = 0; // IPv4 network mask as int
        $this->rs['ipv4router'] = 0;  // IPv4 router address as int
        $this->rs['ipv6'] = ''; // IPv6 configuration (automatic, manual)
        $this->rs['ipv6ip'] = ''; // IPv6 address as string
        $this->rs['ipv6prefixlen'] = 0; // IPv6 prefix length as int
        $this->rs['ipv6router'] = '';  // IPv6 router address as string
        $this->rs['timestamp'] = time();

        $this->idx[] = array('serial');
        $this->idx[] = array('serial', 'service');
				
		// Create table if it does not exist
        $this->create_table();
        
        if($serial and $name)
        {
            $this->retrieve_one('serial=? AND service=?', array($serial, $service));
            $this->serial = $serial;
            $this->service = $service;
        }
        
        return $this;
    }

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author abn290
     **/
    function process($data)
    {
        echo "Network: got data\n";
        
        // Parse network data
        foreach(explode("\n", $data), as $line
        {
            echo $line;
        }
        return;    
        // Remove serial_number from mylist, use the cleaned serial that was provided in the constructor.
        unset($mylist['serial_number']);

        // Set default computer_name
        if( ! isset($mylist['computer_name']) OR trim($mylist['computer_name']) == '')
        {
            $mylist['computer_name'] = 'No name';
        }
        
        $this->timestamp = time();
        $this->merge($mylist)->save();
    }


}
