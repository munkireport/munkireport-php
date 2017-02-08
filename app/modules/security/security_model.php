<?php
class Security_model extends Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'security'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['gatekeeper'] = '';
    	$this->rs['sip'] = '';
    	$this->rs['ssh_users'] = '';
    	$this->rs['ard_users'] = '';
    	$this->rs['firmwarepw'] = '';
        
        // Add indexes
        $this->idx[] = array('serial_number');
        $this->idx[] = array('gatekeeper');
        $this->idx[] = array('sip');
        $this->idx[] = array('ssh_users');
        $this->idx[] = array('ard_users');
        $this->idx[] = array('firmwarepw');

        // Schema version, increment when creating a db migration
        $this->schema_version = 4;
        
        // Create table if it does not exist
        $this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    
    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
	if (strpos($data, '<?xml') === false) {
		// old style txt file data has been passed - throw an error.
		throw new Exception("Error Processing Request: old format data found, please update the security module", 1);	
	}
	else {
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($data);

		$plist = $parser->toArray();

		foreach (array('sip', 'gatekeeper', 'ssh_users', 'ard_users', 'firmwarepw') as $item) {
			if (isset($plist[$item])) {
				$this->$item = $plist[$item];
			} else {
				$this->$item = '';
			}
		}
		$this->save();
	}
    }

    public function get_sip_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN sip = 'Active' THEN 1 END) AS Active,
		COUNT(CASE WHEN sip = 'Disabled' THEN 1 END) AS Disabled
		FROM security
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_gatekeeper_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN gatekeeper = 'Active' THEN 1 END) AS Active,
                COUNT(CASE WHEN gatekeeper = 'Disabled' THEN 1 END) AS Disabled
                FROM security
                LEFT JOIN reportdata USING(serial_number)
                ".get_machine_group_filter();
        return current($this->query($sql));
    }

    public function get_firmwarepw_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN firmwarepw = 'Yes' THEN 1 END) AS enabled,
		COUNT(CASE WHEN firmwarepw = 'No' THEN 1 END) AS disabled
		FROM security
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }
}
