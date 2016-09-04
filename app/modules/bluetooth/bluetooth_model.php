<?php
class Bluetooth_model extends Model
{

    function __construct($serial = '')
    {
        parent::__construct('id', 'bluetooth'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['battery_percent'] = -1; //-1 means unknown
        $this->rs['device_type'] = ''; // status, kb, mouse, trackpad


        // Schema version, increment when creating a db migration
        $this->schema_version = 2;

        // Add indexes
        $this->idx[] = array('serial_number');
        $this->idx[] = array('device_type');

        // Create table if it does not exist
        $this->create_table();
        
        $this->$serial = $serial;

    }
    
    /**
     * Get devices with low battery
     *
     * Select devices with battery level below 15%
     *
     **/
    public function get_low()
    {
        $out = array();
        $sql = "SELECT bluetooth.serial_number, machine.computer_name,
						bluetooth.device_type, bluetooth.battery_percent
						FROM bluetooth
						LEFT JOIN reportdata USING (serial_number)
						LEFT JOIN machine USING (serial_number)
						WHERE (`battery_percent` <= '15') AND  (`device_type` != 'bluetooth_power')
						".get_machine_group_filter('AND');
                        
        foreach ($this->query($sql) as $obj) {
            $out[] = $obj;
        }
        
        return $out;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author clburlison
     **/
    function process($plist)
    {
        if (! $plist) {
            throw new Exception("Error Processing Request: No data found", 1);
        }

        // Delete previous set
        $this->delete_where('serial_number=?', $this->serial_number);
        
        // Check for old-style reports
        if (strpos($plist, '<?xml') === false) {
        // Load legacy support
            require_once(APP_PATH . 'modules/bluetooth/lib/Bt_legacy_support.php');
            $bt = new munkireport\Bt_legacy_support($plist);
            $mylist = $bt->toArray();
        } else {
            require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
            $parser = new CFPropertyList();
            $parser->parse($plist, CFPropertyList::FORMAT_XML);
            $mylist = $parser->toArray();
        }
        
        foreach ($mylist as $key => $value) {
            $this->device_type = strtolower($key);
            $this->battery_percent = $value;

            $this->id = '';
            $this->save();
        }

    }
}
