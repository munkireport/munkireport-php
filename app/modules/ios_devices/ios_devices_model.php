<?php

use CFPropertyList\CFPropertyList;

class Ios_devices_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'ios_devices'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['prefpath'] = '';
        $this->rs['build_version'] = '';
        $this->rs['connected'] = 0;
        $this->rs['device_class'] = '';
        $this->rs['family_id'] = 0;
        $this->rs['firmware_version'] = 0;
        $this->rs['firmware_version_string'] = '';
        $this->rs['software_version'] = 0;
        $this->rs['ios_id'] = '';
        $this->rs['product_type'] = '';
        $this->rs['region_info'] = '';
        $this->rs['serial'] = '';
        $this->rs['use_count'] = 0;
        $this->rs['imei'] = '';
        $this->rs['meid'] = '';

        $this->serial = $serial;
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
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing ios_devices module: No data found");
        } else { 
            
            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);

            // Process incoming ios_devices.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            foreach ($plist as $preffile) {
                foreach (array('serial', 'prefpath', 'build_version', 'connected', 'device_class', 'family_id', 'firmware_version', 'firmware_version_string', 'ios_id', 'product_type', 'region_info', 'use_count', 'imei', 'meid', 'software_version') as $item) {
                    // If key does not exist in $preffile, null it
                    if ( ! array_key_exists($item, $preffile) || $preffile[$item] == '') {
                        $this->$item = null;
                    // Set the db fields to be the same as those in the preference file
                    } else {
                        $this->$item = $preffile[$item];
                    }
                }
            // Save the data
            $this->id = '';
            $this->save(); 
            }
        }
    }
}