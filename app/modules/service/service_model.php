<?php
class Service_model extends Model
{
    
    function __construct($serial = '')
    {
        parent::__construct('id', 'service'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['service_name'] = ''; // Name of service
        $this->rs['service_state'] = ''; // State of service
        $this->rs['timestamp'] = 0; // Timestamp of last update
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('service_name');
        $this->idx[] = array('service_state');
        
        // Create table if it does not exist
        $this->create_table();
    }

    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    function process($data)
    {
        // Delete previous entries
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // Parse log data
        $start = ''; // Start date
        foreach (explode("\n", $data) as $line) {
            if ($line) {
                $parts = explode(" = ", $line);

                if (count($parts) !== 2) {
                    echo 'Invalid log entry: '.$line;
                } else {
                    // Get service name
                    $this->service_name = strtok($parts[0], ':');
                    // Remove quotes and lowercase
                    $this->service_state = strtolower(trim($parts[1], '"'));


                    $this->id = '';
                    $this->timestamp = time();
                    $this->create();
                }
            }
        }
    } // end process()
}
