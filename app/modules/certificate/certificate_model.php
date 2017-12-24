<?php
class Certificate_model extends \Model
{
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'certificate'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['cert_exp_time'] = 0; $this->rt['cert_exp_time'] = 'BIGINT'; // Unix timestamp of expiration time
        $this->rs['cert_path'] = ''; // Path to certificate
        $this->rs['cert_cn'] = ''; // Common name
        $this->rs['issuer'] = ''; //Certificate issuer
        $this->rs['cert_location'] = ''; //Certificate location
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 2;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('cert_exp_time');
        $this->idx[] = array('cert_path');
        $this->idx[] = array('cert_cn');
        $this->idx[] = array('issuer');
        $this->idx[] = array('cert_location');
        
        // Create table if it does not exist
       //$this->create_table();
    }

     public function get_certificates()
     {
        $out = array();
        $sql = "SELECT cert_cn, COUNT(1) AS count
                FROM certificate
                GROUP BY cert_cn
                ORDER BY COUNT DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->cert_cn = $obj->cert_cn ? $obj->cert_cn : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
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

                if (count($parts) !== 5) {
                    echo 'Invalid log entry: '.$line;
                } else {
                    // Convert unix timestamp string to int
                    $this->cert_exp_time = intval($parts[0]);
                    // Trim path to 255 chars
                    $this->cert_path = substr($parts[1], 0, 254);
                    // Get common name out of subject
                    if (preg_match('/CN = ([^,|\n]+)/', $parts[2], $matches)) {
                        $this->cert_cn = $matches[1];
                    } else {
                        $this->cert_cn = 'Unknown';
                    }
                    if (preg_match('/CN = ([^,|\n]+)/', $parts[3], $matches)) {
                        $this->issuer = $matches[1];
                    } else {
                        $this->issuer = 'Unknown';
                    }
                    
                    $this->cert_location = $parts[4];

                    $this->id = '';
                    $this->timestamp = time();
                    $this->create();
                    
                    // Check for errors
                    if ($this->cert_exp_time < $now) {
                        $errors[] = array(
                            'type' => 'danger',
                            'msg' => 'cert.expired',
                            'data' => json_encode(array(
                                'name' => $this->cert_cn,
                                'timestamp' => $this->cert_exp_time
                            ))
                        );
                    } elseif ($this->cert_exp_time < $four_weeks) {
                        $errors[] = array(
                            'type' => 'warning',
                            'msg' => 'cert.expire_warning',
                            'data' => json_encode(array(
                                'name' => $this->cert_cn,
                                'timestamp' => $this->cert_exp_time
                            ))
                        );
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
                $last_error = array();
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
    public function get_stats()
    {
        $now = time();
        $one_month = $now + 3600 * 24 * 30 * 1;
        $sql = "SELECT COUNT(1) as total, 
			COUNT(CASE WHEN cert_exp_time < '$now' THEN 1 END) AS expired, 
			COUNT(CASE WHEN cert_exp_time BETWEEN $now AND $one_month THEN 1 END) AS soon,
			COUNT(CASE WHEN cert_exp_time > $one_month THEN 1 END) AS ok
			FROM certificate
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
        return current($this->query($sql));
    }
}
