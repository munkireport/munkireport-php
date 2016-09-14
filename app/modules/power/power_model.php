<?php
class Power_model extends Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'power'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['manufacture_date'] = '';
        $this->rs['design_capacity'] = 1;
        $this->rs['max_capacity'] = 1;
        $this->rs['max_percent'] = 100;
        $this->rs['current_capacity'] = 0;
        $this->rs['current_percent'] = 0;
        $this->rs['cycle_count'] = 0;
        $this->rs['temperature'] = 0;
        $this->rs['condition'] = '';
        $this->rs['timestamp'] = 0; // Unix time when the report was uploaded
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        // Create table if it does not exist
        $this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    /**
     * Get Power statistics
     *
     *
     **/
    public function get_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN max_percent>89 THEN 1 END) AS success,
                        COUNT(CASE WHEN max_percent BETWEEN 80 AND 89 THEN 1 END) AS warning,
                        COUNT(CASE WHEN max_percent<80 THEN 1 END) AS danger
                        FROM power
                        LEFT JOIN reportdata USING(serial_number)
                        ".get_machine_group_filter();
        return current($this->query($sql));
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
        // If data is empty, remove record
        if (! $data) {
            $this->delete();
            return;
        }

        // Translate network strings to db fields
        $translate = array(
            'manufacture_date = ' => 'manufacture_date',
            'design_capacity = ' => 'design_capacity',
            'max_capacity = ' => 'max_capacity',
            'current_capacity = ' => 'current_capacity',
            'cycle_count = ' => 'cycle_count',
            'temperature = ' => 'temperature',
            'condition = ' => 'condition');
        // Reset values
        $this->manufacture_date = '';
        $this->design_capacity = 1;
        $this->max_capacity = 1;
        $this->max_percent = 100;
        $this->current_capacity = 0;
        $this->current_percent = 0;
        $this->cycle_count = 0;
        $this->temperature = 0;
        $this->condition = '';
        $this->timestamp = 0;
        // Parse data
        foreach(explode("\n", $data) as $line) {
            // Translate standard entries
            foreach($translate as $search => $field) {
                
                if(strpos($line, $search) === 0) {
                    
                    $value = substr($line, strlen($search));
                    
                    $this->$field = $value;
                    break;
                }
            } 
        } //end foreach explode lines

        if ( $this->condition === "No Battery") {
            $this->manufacture_date = '';
            $this->design_capacity = 0;
            $this->max_capacity = 0;
            $this->current_capacity = 0;
            $this->cycle_count = 0;
            $this->temperature = 0;
        }
        
        // Calculate maximum capacity as percentage of original capacity
        if ( $this->design_capacity > 0) {
            $this->max_percent = round(($this->max_capacity / $this->design_capacity * 100 ), 0 );
        } else {
            $this->max_percent = 0;
        }
        
        // Calculate percentage of current maximum capacity
        if ( $this->max_capacity > 0) {
            $this->current_percent = round(($this->current_capacity / $this->max_capacity * 100 ), 0 );
        } else {
            $this->current_percent = 0;
        }	
        
        // Convert battery manufacture date to calendar date.
        // Bits 0...4 => day (value 1-31; 5 bits)
        // Bits 5...8 => month (value 1-12; 4 bits)
        // Bits 9...15 => years since 1980 (value 0-127; 7 bits)
        
        $ManufactureDate = $this->manufacture_date;
        
        $mfgday = $this->manufacture_date & 31;
        $mfgmonth = ($this->manufacture_date >> 5) & 15;
        $mfgyear = (($this->manufacture_date >> 9) & 127) + 1980;
        
        $this->manufacture_date = sprintf("%d-%02d-%02d", $mfgyear, $mfgmonth, $mfgday);
        
        //timestamp added by the server
        $this->timestamp = time();
            
        $this->save();
    }
}
