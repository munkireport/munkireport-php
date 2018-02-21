<?php

use CFPropertyList\CFPropertyList;

class Location_model extends \Model
{

    // Defaults array
    private $defaults = array();

    public function __construct($serial = '')
    {
        parent::__construct('id', 'location'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['address'] = '';
        $this->rs['altitude'] = 0;
        $this->rs['currentstatus'] = '';
        $this->rs['ls_enabled'] = 0;
        $this->rs['lastlocationrun'] = '';
        $this->rs['lastrun'] = '';
        $this->rs['latitude'] = 0.0;
        $this->rs['latitudeaccuracy'] = 0;
        $this->rs['longitude'] = 0.0;
        $this->rs['longitudeaccuracy'] = 0;
        $this->rs['stalelocation'] = '';

        // Store default values
        $this->defaults = $this->rs;

        // Add indexes
        $this->idx[] = array('address');
        $this->idx[] = array('currentstatus');

        // Schema version, increment when creating a db migration
        $this->schema_version = 1;

        // Create table if it does not exist
       //$this->create_table();

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }
    
    /**
     * Retrieve locations and names to render on a google map
     *
     *
     **/
    public function get_map_data()
    {
        // FIXME Does not account for business units!!!
        $out = array();
        $filter = get_machine_group_filter();
        $sql = "SELECT location.serial_number AS serial_number, latitude, longitude, long_username,
					computer_name
				FROM location
				LEFT JOIN reportdata USING (serial_number)
				LEFT JOIN machine USING (serial_number)
				$filter
				AND currentstatus = 'Successful'";
                
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
     *
     **/
    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);

        $plist = $parser->toArray();

        // Translate location strings to db fields
        $translate = array(
            'Address' => 'address',
            'Altitude' => 'altitude',
            'CurrentStatus' => 'currentstatus',
            'LS_Enabled' => 'ls_enabled',
            'LastLocationRun' => 'lastlocationrun',
            'LastRun' => 'lastrun',
            'Latitude' => 'latitude',
            'LatitudeAccuracy' => 'latitudeaccuracy',
            'Longitude' => 'longitude',
            'LongitudeAccuracy' => 'longitudeaccuracy',
            'StaleLocation' => 'stalelocation');

        // Parse data
        foreach ($translate as $search => $field) {
            if (isset($plist[$search])) {
                $this->$field = $plist[$search];
            } else {
                $this->$field = $this->defaults[$field];
            }
        }

        $this->save();
    }
}
