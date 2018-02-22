<?php
// @author gmarnin

class Firmware_escrow_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'firmware_escrow'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['enabled_date'] = '';
        $this->rs['firmware_password'] = '';
        $this->rs['firmware_mode'] = '';

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
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

        // Translate network strings to db fields
                $translate = array(
                    'EnabledDate = ' => 'enabled_date',
                    'FirmwarePassword = ' => 'firmware_password',
                    'FirmwareMode = ' => 'firmware_mode');

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
