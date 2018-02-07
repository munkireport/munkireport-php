<?php

use CFPropertyList\CFPropertyList;

class Mdm_status_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'mdm_status'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['mdm_enrollment_type'] = '';
        $this->rs['mdm_enrolled'] = '';
        
        // Add indexes
        $this->idx[] = array('serial_number');
        $this->idx[] = array('mdm_enrollment_type');
        $this->idx[] = array('mdm_enrolled');

        // Schema version, increment when creating a db migration
        $this->schema_version = 1;
        
        // Create table if it does not exist
       //$this->create_table();
        
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
    		$parser = new CFPropertyList();
    		$parser->parse($data);

    		$plist = $parser->toArray();

    		foreach (array('mdm_enrollment_type', 'mdm_enrolled') as $item) {
    			if (isset($plist[$item])) {
    				$this->$item = $plist[$item];
    			} else {
    				$this->$item = '';
    			}
    		}
    		$this->save();
    	}
    }

    public function get_mdm_enrollment_type_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN mdm_enrollment_type = 'DEP' THEN 1 END) AS dep, 
			COUNT(CASE WHEN mdm_enrollment_type = 'Non-DEP' THEN 1 END) AS non_dep
			FROM mdm_status
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
        return current($this->query($sql));
    }

    public function get_mdm_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN mdm_enrolled = 'Yes' THEN 1 END) AS mdm_yes, 
			COUNT(CASE WHEN mdm_enrolled = 'Yes (User Approved)' THEN 1 END) AS mdm_yes_ua,
			COUNT(CASE WHEN mdm_enrolled = 'No' THEN 1 END) AS mdm_no 
			FROM mdm_status
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
        return current($this->query($sql));
    }

}
