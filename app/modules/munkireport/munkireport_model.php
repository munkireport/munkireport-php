<?php

use CFPropertyList\CFPropertyList;
use munkireport\models\Hash;

class Munkireport_model extends \Model
{

    public function __construct($serial_number='')
    {
        parent::__construct('id', 'munkireport'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial_number;
        $this->rs['runtype'] = '';
        $this->rs['version'] = '';
        $this->rs['errors'] = 0;
        $this->rs['warnings'] = 0;
        $this->rs['manifestname'] = '';
        $this->rs['error_json'] = '';
        $this->rs['warning_json'] = '';
        $this->rs['starttime'] = '';
        $this->rs['endtime'] = '';
        $this->rs['timestamp'] = '';

        if ($serial_number) {
            $this->retrieve_record($serial_number);
            $this->serial_number = $serial_number;
        }
    }

    /**
     * Get manifests statistics
     *
     *
     **/
    public function get_manifest_stats()
    {
        $out = array();
        $filter = get_machine_group_filter();
        $sql = "SELECT COUNT(1) AS count, manifestname
            FROM munkireport
            LEFT JOIN reportdata USING (serial_number)
            $filter
            GROUP BY manifestname
            ORDER BY count DESC";

        foreach ($this->query($sql) as $obj) {
            $obj->manifestname = $obj->manifestname ? $obj->manifestname : 'Unknown';
            $out[] = $obj;
        }

        return $out;
    }

    /**
     * Get munki versions
     *
     *
     **/
    public function get_versions()
    {
        $filter = get_machine_group_filter();
        $sql = "SELECT version, COUNT(1) AS count
                FROM munkireport
                LEFT JOIN reportdata USING (serial_number)
                $filter
                GROUP BY version
                ORDER BY COUNT DESC";
        return $this->query($sql);
    }

    /**
     * Get statistics
     *
     * Get object describing statistics
     *
     * @param integer hours hours of statistics
     **/
    public function get_stats($hours = 24)
    {
        $timestamp = date('Y-m-d H:i:s', time() - 60 * 60 * $hours);
        $sql = "SELECT
            SUM(errors > 0) as error,
            SUM(warnings > 0) as warning
            FROM munkireport
            LEFT JOIN reportdata USING (serial_number)
            ".get_machine_group_filter()."
            AND munkireport.timestamp > '$timestamp'";

        return current($this->query($sql));
    }


    public function process($plist)
    {
        $this->timestamp = date('Y-m-d H:i:s');

        if (! $plist) {
            throw new Exception("Error Processing Request: No property list found", 1);
        }

        $parser = new CFPropertyList();
        $parser->parse($plist, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();

        // Translate plist keys to db keys
        $translate = array(
            'ManagedInstallVersion' => 'version',
            'ManifestName' => 'manifestname',
            'RunType' => 'runtype',
            'StartTime' => 'starttime',
            'EndTime' => 'endtime',
        );

        foreach ($translate as $key => $dbkey) {
            if (array_key_exists($key, $mylist)) {
                $this->$dbkey = $mylist[$key];
            }
        }

        // Parse errors and warnings
        $errorsWarnings = array('Errors' => 'error_json', 'Warnings' => 'warning_json');
        foreach ($errorsWarnings as $key => $json) {
            $dbkey = strtolower($key);
            if (isset($mylist[$key]) && is_array($mylist[$key])) {
                // Store count
                $this->rs[$dbkey] = count($mylist[$key]);

                // Store json
                $this->rs[$json] = json_encode($mylist[$key]);
            } else {
                // reset
                $this->rs[$dbkey] = 0;
                $this->rs[$json] = json_encode(array());
            }
        }

        // Store record
        $this->save();

        // Store apropriate event:
        if ($this->rs['errors'] == 1) { // Errors is a protected name
            $this->store_event(
                'danger',
                'munki.error',
                json_encode(array('error' => truncate_string($mylist['Errors'][0])))
            );
        } elseif ($this->rs['errors'] > 1) { // Errors is a protected name
            $this->store_event(
                'danger',
                'munki.error',
                json_encode(array('count' => $this->rs['errors']))
            );
        } elseif ($this->warnings == 1) {
            $this->store_event(
                'warning',
                'munki.warning',
                json_encode(array('warning' => truncate_string($mylist['Warnings'][0])))
            );
        } elseif ($this->warnings > 1) {
            $this->store_event(
                'warning',
                'munki.warning',
                json_encode(array('count' => $this->warnings))
            );
        } else {
            // Delete event
            $this->delete_event();
        }

        // Legacy support: check if we got an old style report
        if (array_key_exists('ManagedInstalls', $mylist)) {
            $legacyObj = new munkireport\lib\Legacy_munkireport;
            $install_list = $legacyObj->parse($mylist)->getList();

            // Calculate hash and check with hash
            $myHash = md5(serialize($install_list));
            $hashObj = new Hash();
            $bindings = array($this->serial_number, 'managedinstalls');
            $hashObj->retrieveOne('serial=? AND name=?', $bindings);

            // Compare hash with stored hash
            if ($hashObj->hash != $myHash) {
                // Instantiate managedinstalls model
                $managedinstallsObj = new Managedinstalls_model;
                // Store data in managedinstalls
                $managedinstallsObj->setSerialNumber($this->serial_number);
                $managedinstallsObj->processData($install_list);

                $hashObj->serial = $this->serial_number;
                $hashObj->name = 'managedinstalls';
                $hashObj->hash = $myHash;
                $hashObj->save();
            }
        }


        return $this;
    }
}
