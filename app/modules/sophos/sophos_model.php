<?php

use CFPropertyList\CFPropertyList;

class Sophos_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'sophos');
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['installed'] = '';
        $this->rs['running'] = '';
        $this->rs['product_version'] = '';
        $this->rs['engine_version'] = '';
        $this->rs['virus_data_version'] = '';
        $this->rs['user_interface_version'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial_number = $serial;
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
        $parser = new CFPropertyList();
        $parser->parse($data);
        $plist = $parser->toArray();

        $this->installed = $plist['Installed'];
        $this->running = $plist['Running'];
        $this->engine_version = $plist['Versions']['Engine version'];
        $this->product_version = $plist['Versions']['Product version'];
        $this->user_interface_version = $plist['Versions']['User interface version'];
        $this->virus_data_version = $plist['Versions']['Virus data version'];
        $this->save();

    }

    public function get_sophos_install_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN installed = 'Sophos Business' THEN 1 END) AS 'business', COUNT(CASE WHEN installed = 'Sophos Central' THEN 1 END) AS 'central', COUNT(CASE WHEN installed IS NULL THEN 1 END) AS 'notinstalled'
            FROM sophos
            LEFT JOIN reportdata USING(serial_number)
            ".get_machine_group_filter();
        return current($this->query($sql));
    }


    public function get_sophos_running_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN running = 1 THEN 1 END) as Running, COUNT(CASE WHEN running = 0 THEN 1 END) as 'Not Running'
            FROM sophos
            LEFT JOIN reportdata USING(serial_number)
            ".get_machine_group_filter();
        return current($this->query($sql));
    }

}
