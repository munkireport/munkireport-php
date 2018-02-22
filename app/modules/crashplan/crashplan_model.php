<?php
class Crashplan_model extends \Model
{
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'crashplan'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['destination'] = ''; // Name of destination
        $this->rs['last_success'] = 0; // Timestamp of last successful backup
        $this->rs['duration'] = 0; // duration in seconds
        $this->rs['last_failure'] = 0; // Timestamp of last failed backup
        $this->rs['reason'] = ''; // Reason of last failure
        
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('reason');
        
        // Create table if it does not exist
       //$this->create_table();
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
        $serial_number = $this->serial_number;
        $this->deleteWhere('serial_number=?', $serial_number);
        
        //
        $messages = array(
            'errors' => array(),
            'warnings' => array()
        );
        
        // Parse data
        $lines = explode("\n", $data);
        $headers =  str_getcsv(array_shift($lines));
        foreach ($lines as $line) {
            if ($line) {
                $this->merge(array_combine($headers, str_getcsv($line)));
                
                // Only store entry when there is at least one date
                if ($this->last_success > 0 or $this->last_failure > 0) {
                    $this->id = '';
                    $this->serial_number = $serial_number;
                    $this->save();
                    
                    // Events
                    if ($this->last_success < $this->last_failure) {
                        $messages['errors'][] = array(
                            'destination' => $this->destination,
                            'reason' => $this->reason,
                        );
                    }
                }
            }
        }
        
        // Only store if there is data
        if ($messages['errors']) {
            $type = 'danger';
            $msg = 'crashplan.backup_failed';
            if (count($messages['errors']) == 1) {
                $out = array_pop($messages['errors']);
            } else {
                $out = array('count' => count($messages['errors']));
            }
            $data = json_encode($out);
            $this->store_event($type, $msg, $data);
        } else {
            $this->delete_event();
        }
    } // end process()
    
    /**
     * Get statistics
     *
     * @return void
     * @author
     **/
    public function get_stats($hours)
    {
        $now = time();
        $today = $now - 3600 * 24;
        $week_ago = $now - 3600 * 24 * 7;
        $month_ago = $now - 3600 * 24 * 30;
        $sql = "SELECT COUNT(1) as total, 
			COUNT(CASE WHEN last_success > '$today' THEN 1 END) AS today, 
			COUNT(CASE WHEN last_success BETWEEN '$week_ago' AND '$today' THEN 1 END) AS lastweek,
			COUNT(CASE WHEN last_success < '$week_ago' THEN 1 END) AS week_plus
			FROM crashplan
			LEFT JOIN reportdata USING (serial_number)
			".get_machine_group_filter();
        return current($this->query($sql));
    }
}
