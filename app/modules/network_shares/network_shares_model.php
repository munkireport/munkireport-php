<?php

use CFPropertyList\CFPropertyList;

class Network_shares_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'network_shares'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['name'] = '';
		$this->rs['mntfromname'] = '';
		$this->rs['fstypename'] = '';
		$this->rs['fsmtnonname'] = '';
		$this->rs['automounted'] = 0; // True or False


		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('name');
		$this->idx[] = array('mntfromname');
		$this->idx[] = array('fstypename');
		$this->idx[] = array('fsmtnonname');
		$this->idx[] = array('automounted');

		// Create table if it does not exist
		//$this->create_table();

		$this->serial_number = $serial;
	}

	// ------------------------------------------------------------------------


     /**
     * Get network shares for widget
     *
     **/
     public function get_network_shares()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN name <> '' AND name IS NOT NULL THEN 1 END) AS count, name
                FROM network_shares
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY name
                ORDER BY count DESC";

        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->name = $obj->name ? $obj->name : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }


	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author tuxudo
	 **/
	function process($plist)
	{

		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}

		// Delete previous set
		$this->deleteWhere('serial_number=?', $this->serial_number);

		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();

		$typeList = array(
			'name' => '',
			'mntfromname' => '',
			'fstypename' => '',
			'fsmtnonname' => '',
			'automounted' => 0
		);

		foreach ($myList as $device) {
			// Check if we have a name
			if( ! array_key_exists("name", $device)){
				continue;
			}

			// Network shares to exclude
            $excludeshares = array("/net","/home","/Volumes/MobileBackups","/Volumes/MobileBackups 1","/Volumes/MobileBackups 2","/Network/Servers");
            if (in_array($device['fsmtnonname'], $excludeshares)) {
				continue;
			}

			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $device))
				{
					$this->rs[$key] = $device[$key];
				}
			}

			// Save network share
			$this->id = '';
			$this->save();
		}
	}
}
