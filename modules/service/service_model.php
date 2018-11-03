<?php
class Service_model extends \Model
{
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'service'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['service_name'] = ''; // Name of service
        $this->rs['service_state'] = ''; // State of service
        $this->rs['timestamp'] = 0; // Timestamp of last update
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
