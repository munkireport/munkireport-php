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
        // Schema version, increment when creating a db migration
        $this->schema_version = 003;
        
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
	// If we are passed something other than a plist, the client the old data format.
	// Process it using the old way!
	if (strpos($data, '<?xml') === false) {    
        // Delete previous entries
        $this->deleteWhere('serial_number=?', $this->serial_number);
        
        // Translate security strings to db fields
        $translate = array(
            'Gatekeeper: ' => 'gatekeeper',
            'SIP: ' => 'sip');
	//clear any previous data we had
        foreach ($translate as $search => $field) {
            $this->$field = '';
        }
        // Parse data
        foreach (explode("\n", $data) as $line) {
            // Translate standard entries
            foreach ($translate as $search => $field) {
                if (strpos($line, $search) === 0) {
                    $value = substr($line, strlen($search));
                    
                    $this->$field = $value;
                    # Check if this is the last field
                    if ($field == 'sip') {
                        $this->id = '';
                        $this->save();
                    }
                    break;
                }
            }
	}
	}

	else { // we have been sent a plist - process it the new way.
	require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
	$parser = new CFPropertyList();
	$parser->parse($data);

	$plist = $parser->toArray();
	$this->deleteWhere('serial_number=?', $this->serial_number);
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
}
