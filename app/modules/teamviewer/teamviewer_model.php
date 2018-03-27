<?php

use CFPropertyList\CFPropertyList;

class Teamviewer_model extends \Model 
{

    public function __construct($serial='')
    {
        parent::__construct('id', 'teamviewer'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['always_online'] = 0;
        $this->rs['autoupdatemode'] = 0;
        $this->rs['clientid'] = 0;
        $this->rs['clientic'] = 0;
        $this->rs['had_a_commercial_connection'] = 0;
        $this->rs['ipc_port_service'] = 0;
        $this->rs['lastmacused'] = '';
        $this->rs['licensetype'] = 0;
        $this->rs['midversion'] = 0;
        $this->rs['moverestriction'] = 0;
        $this->rs['security_adminrights'] = 0;
        $this->rs['security_passwordstrength'] = 0;
        $this->rs['version'] = '';
        $this->rs['update_available'] = 0;
        $this->rs['is_not_first_run_without_connection'] = 0;
        $this->rs['is_not_running_test_connection'] = 0;
        $this->rs['meeting_username'] = '';
        $this->rs['prefpath'] = '';
        $this->rs['updateversion'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

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

        if (! $data) {
            print_r("Error Processing Request: No TeamViewer data found");
        }    

        // Delete previous entries
        $this->deleteWhere('serial_number=?', $this->serial_number);
        
        // Process incoming teamviewer.plist
        $parser = new CFPropertyList();
        $parser->parse($data);
        $plist = $parser->toArray();
        
        foreach ($plist as $preffile) {
            foreach (array('always_online', 'autoupdatemode', 'clientid', 'clientic', 'had_a_commercial_connection', 'ipc_port_service', 'lastmacused', 'licensetype', 'midversion', 'moverestriction', 'security_adminrights', 'security_passwordstrength', 'version', 'update_available', 'is_not_first_run_without_connection', 'is_not_running_test_connection', 'meeting_username', 'prefpath', 'updateversion') as $item) {
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
