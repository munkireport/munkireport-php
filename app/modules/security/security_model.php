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
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
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
	require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
	$parser = new CFPropertyList();
	$parser->parse($data);

	$plist = $parser->toArray();

	foreach (array('sip', 'gatekeeper') as $item) {
		if (isset($plist[$item])) {
			$this->$item = $plist[$item];
		} else {
			$this->$item = '';
		}
	}
	$this->save();
    }
}
