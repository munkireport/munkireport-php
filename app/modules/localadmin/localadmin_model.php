<?php
class Localadmin_model extends Model
{

    public function __construct($serial = '')
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

     public function get_localadmin()
     {
        $out = array();
        //Check if config threshold is set for number of admins to show
        $threshold=2;
        if(!empty(conf('local_admin_threshold'))) {
            $threshold=conf('local_admin_threshold');
            }
        $sql = "SELECT machine.serial_number, computer_name,
                    LENGTH(users) - LENGTH(REPLACE(users, ' ', '')) + 1 AS count,
                    users
                    FROM localadmin
                    LEFT JOIN machine USING (serial_number)
                    WHERE localadmin.users LIKE '%'  
                    ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ($obj->count >= $threshold) {
                $obj->users = $obj->users ? $obj->users : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author AvB
     **/
    public function process($data)
    {
        $this->users = trim($data);
        $this->save();
    }
}
