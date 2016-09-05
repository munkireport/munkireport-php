<?php
class Certificate_model extends Model
{
    
    function __construct($serial = '')
    {
        parent::__construct('id', 'certificate'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['cert_exp_time'] = 0; // Unix timestamp of expiration time
        $this->rs['cert_path'] = ''; // Path to certificate
        $this->rs['cert_cn'] = ''; // Common name
        $this->rs['timestamp'] = 0; // Timestamp of last update
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('cert_exp_time');
        $this->idx[] = array('timestamp');
        
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
        // Delete previous set
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // Parse log data
        $start = ''; // Start date
        $errors = array();
        $now = time();
        $four_weeks = $now + 3600 * 24 * 7 * 4;

        foreach (explode("\n", $data) as $line) {
            if ($line) {
                $parts = explode("\t", $line);

                if (count($parts) !== 3) {
                    echo 'Invalid log entry: '.$line;
                } else {
                    // Convert unix timestamp string to int
                    $this->cert_exp_time = intval($parts[0]);
                    // Trim path to 255 chars
                    $this->cert_path = substr($parts[1], 0, 254);
                    // Get common name out of subject
                    if (preg_match('/subject= CN = ([^,]+)/', $parts[2], $matches)) {
                        $this->cert_cn = $matches[1];
                    } else {
                        $this->cert_cn = 'Unknown';
                    }

                    $this->id = '';
                    $this->timestamp = time();
                    $this->create();
                    
                    // Check for errors
                    if ($this->cert_exp_time < $now) {
                        $errors[] = [
                        'type' => 'danger',
                        'msg' => 'cert.expired',
                        'data' => json_encode(array(
                            'name' => $this->cert_cn,
                            'timestamp' => $this->cert_exp_time
                        ))
                        ];
                    } elseif ($this->cert_exp_time < $four_weeks) {
                        $errors[] = [
                        'type' => 'warning',
                        'msg' => 'cert.expire_warning',
                        'data' => json_encode(array(
                            'name' => $this->cert_cn,
                            'timestamp' => $this->cert_exp_time
                        ))
                        ];
                    }
                }
            }
        }// end foreach()
        
        if (! $errors) {
            $this->delete_event();
        } else {
            if (count($errors) == 1) {
                $error = array_pop($errors);
                $this->store_event($error['type'], $error['msg'], $error['data']);
            } else {
                // Loop through errors and submit stats
                $error_count = 0;
                $last_error = [];
                $warning_count = 0;
                // Search through errors and warnings
                foreach ($errors as $error) {
                    if ($error['type'] == 'danger') {
                        $last_error = $error;
                        $error_count ++;
                    }
                    if ($error['type'] == 'warning') {
                        $warning_count ++;
                    }
                }
                // If errors, ignore warnings
                if ($error_count) {
                    $type = 'error';
                    if ($error_count == 1) {
                        $msg = $last_error['msg'];
                        $data = $last_error['data'];
                    } else {
                        $msg = 'cert.multiple_errors';
                        $data = $error_count;
                    }
                } else {
                    $type = 'warning';
                    $msg = 'cert.multiple_warnings';
                    $data = $warning_count;
                }
                $this->store_event($type, $msg, $data);
            }
            
        }

    } // end process()

    /**
     * Get statistics
     *
     * @return void
     * @author
     **/
    function get_stats()
    {
        $now = time();
        $three_months = $now + 3600 * 24 * 30 * 3;
        $sql = "SELECT COUNT(1) as total, 
			COUNT(CASE WHEN cert_exp_time < '$now' THEN 1 END) AS expired, 
			COUNT(CASE WHEN cert_exp_time BETWEEN $now AND $three_months THEN 1 END) AS soon,
			COUNT(CASE WHEN cert_exp_time > $three_months THEN 1 END) AS ok
			FROM certificate
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
        return current($this->query($sql));
    }
}
