<?php

use CFPropertyList\CFPropertyList;

class Bluetooth_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'bluetooth'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['battery_percent'] = -1; //-1 means unknown
        $this->rs['device_type'] = ''; // status, kb, mouse, trackpad

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
    public function process($plist)
    {
        if (! $plist) {
            throw new Exception("Error Processing Request: No data found", 1);
        }

        // Delete previous set
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // Check for old-style reports
        if (strpos($plist, '<?xml') === false) {
        // Load legacy support
            require_once(APP_PATH . 'modules/bluetooth/lib/Bt_legacy_support.php');
            $bt = new munkireport\Bt_legacy_support($plist);
            $mylist = $bt->toArray();
        } else {
            $parser = new CFPropertyList();
            $parser->parse($plist, CFPropertyList::FORMAT_XML);
            $mylist = $parser->toArray();
        }

        foreach ($mylist as $key => $value) {

            $this->device_type = str_replace(' ', '_', strtolower($key));
            if($this->device_type == "bluetooth_power" && ! is_bool($value)){
                $this->battery_percent = -1;
            } else if($this->device_type == "bluetooth_power"){
                $this->battery_percent = $value == true ? 1 : 0;
            } else {
                $this->battery_percent = $value;
            }

            $this->id = '';
            $this->save();
        }
    }
}
