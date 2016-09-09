<?php
class Localadmin_model extends Model
{

    function __construct($serial = '')
    {
        parent::__construct('id', 'localadmin'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['users'] = '';

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
     * @author AvB
     **/
    function process($data)
    {
        $this->users = trim($data);
        $this->save();
    }
}
