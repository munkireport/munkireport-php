<?php
class Servermetrics_model extends Model
{

    // Field order as sent by postflight and served by get_data().
    private $keys = array(
        'afp_sessions',
        'smb_sessions',
        'caching_cache_toclients',
        'caching_origin_toclients',
        'caching_peers_toclients',
        'cpu_user',
        'memory_wired',
        'memory_active',
        'cpu_idle',
        'memory_free',
        'network_in',
        'memory_pressure',
        'cpu_system',
        'network_out',
        'cpu_nice',
        'memory_inactive'
    );

    public function __construct($serial = '')
    {
        parent::__construct('id', 'servermetrics'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['afp_sessions'] = 0; // number of afp connections
        $this->rs['smb_sessions'] = 0; // number of smb connections
        $this->rs['caching_cache_toclients'] = 0.0; //
        $this->rs['caching_origin_toclients'] = 0.0; //
        $this->rs['caching_peers_toclients'] = 0.0; //
        $this->rs['cpu_user'] = 0.0; //
        $this->rs['cpu_idle'] = 0.0; //
        $this->rs['cpu_system'] = 0.0; //
        $this->rs['cpu_nice'] = 0.0; //
        $this->rs['memory_wired'] = 0.0; //
        $this->rs['memory_active'] = 0.0; //
        $this->rs['memory_inactive'] = 0.0; //
        $this->rs['memory_free'] = 0.0; //
        $this->rs['memory_pressure'] = 0.0; //
        $this->rs['network_in'] = 0.0; //
        $this->rs['network_out'] = 0.0; //
        $this->rs['datetime'] = ''; // Datetime from record

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;

        //indexes to optimize queries
        $this->idx[] = array('serial_number');
        $this->idx[] = array('datetime');

        // Create table if it does not exist
        $this->create_table();

        $this->serial_number = $serial;
    }
    // ------------------------------------------------------------------------

    /**
     * Get data for serial
     *
     * @return array data
     * @author
     **/
    public function get_data($serial_number, $hours = 24)
    {
        $out = array();

        if (authorized_for_serial($serial_number)) {
            $date = new DateTime();
            $date->sub(new DateInterval('PT'.$hours.'H'));
            $fromdate = $date->format('Y-m-d H:i:s');

            $what = implode(',', $this->keys) . ',datetime';
            $where = 'serial_number = ? AND datetime > ? ORDER BY datetime';
            foreach ($this->select($what, $where, array($serial_number, $fromdate), PDO::FETCH_NUM) as $row) {
                $key = array_pop($row);
                $row = array_map('floatval', $row);
                $out[$key] = $row;
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
        // If data is empty, throw error
        if (! $data) {
            throw new Exception("Error Processing Server Metrics Module Request: No data found", 1);
        }
        
        // Delete previous set (this is expensive)
        $this->deleteWhere('serial_number=?', $this->serial_number);

        try {
            foreach (json_decode($data) as $date => $values) {
            // Only store if there's at least one value > 0
                if (array_sum($values)) {
                    $this->id = 0;
                    $this->datetime = $date;
                    $this->merge(array_combine($this->keys, $values))->save();
                }
            } //end foreach
        } catch (Exception $e) {
            echo 'Failed to decode data';
        }
    }
}
