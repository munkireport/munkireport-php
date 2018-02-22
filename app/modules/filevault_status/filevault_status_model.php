<?php
class Filevault_status_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'filevault_status'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['filevault_status'] = '';
        $this->rs['filevault_users'] = '';

        // Schema version, increment when creating a db migration
        $this->schema_version = 2;
        
        // Add indexes
        $this->idx[] = array('filevault_status');
        $this->idx[] = array('filevault_users');

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
     * @author gmarnin
     **/
    public function process($data)
    {
        // process copied from network model. Translate strings to db fields. needed? . error proof?
            $translate = array('fv_users = ' => 'filevault_users');

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
                    break;
                }
            }
        } //end foreach explode lines
        $this->save();
    }
}
