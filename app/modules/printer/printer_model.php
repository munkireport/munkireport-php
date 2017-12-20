<?php
class Printer_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'printer'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['name'] = '';
        $this->rs['ppd'] = '';
        $this->rs['driver_version'] = '';
        $this->rs['url'] = '';
        $this->rs['default_set'] = '';
        $this->rs['printer_status'] = '';
        $this->rs['printer_sharing'] = '';
        
        $this->idx[] = array('serial_number');
        $this->idx[] = array('name');
        $this->idx[] = array('ppd');
        $this->idx[] = array('url');
        $this->idx[] = array('default_set');
        $this->idx[] = array('printer_status');
        $this->idx[] = array('printer_sharing');

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
     * Get printer names for widget
     *
     **/
    public function get_printers()
    {
        $out = array();
        $sql = "SELECT COUNT(1) AS count, name 
				    FROM printer
				    LEFT JOIN reportdata USING (serial_number)
                    ".get_machine_group_filter()."
                    GROUP BY name
                    ORDER BY count DESC";

        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->name = $obj->name ? $obj->name : 'Unknown';
                $out[] = $obj;
            }
        }
        
        return $out;
    }
    
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        // Delete previous entries
        $this->deleteWhere('serial_number=?', $this->serial_number);
        
        // Translate printer strings to db fields
        $translate = array(
            'Name: ' => 'name',
            'PPD: ' => 'ppd',
            'Driver Version: ' => 'driver_version',
            'URL: ' => 'url',
            'Default Set: ' => 'default_set',
            'Printer Status: ' => 'printer_status',
            'Printer Sharing: ' => 'printer_sharing');

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
                    if ($field == 'printer_sharing') {
                        $this->id = '';
                        $this->save();
                    }
                    break;
                }
            }
        } //end foreach explode lines
        
        
    //	throw new Exception("Error Processing Request", 1);
    }
}
