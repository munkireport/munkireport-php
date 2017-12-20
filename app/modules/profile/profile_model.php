<?php
class Profile_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'profile'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; //$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['profile_uuid'] = ''; //
        $this->rs['profile_name'] = ''; //
        $this->rs['profile_removal_allowed'] = ''; //Yes or No
        $this->rs['payload_name'] = ''; //
        $this->rs['payload_display'] = ''; //
        $this->rs['payload_data'] = '';
        $this->rt['payload_data'] = 'BLOB'; // Payload can exceed 255 chars
        $this->rs['timestamp'] = 0; // Unix time when the report was uploaded
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        //indexes to optimize queries
        $this->idx[] = array('profile_uuid');
        $this->idx[] = array('serial_number');
        
        // Create table if it does not exist
       //$this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    // ------------------------------------------------------------------------
    /**
     * Format profile data to make it prettier
     *
     * @param json string json_string
     *
     **/
    public function json_to_html($json_string)
    {
        # Try to make it prettier
        $json_string = str_replace('\n', '<br />', $json_string);
        $json_string = str_replace(array('\\"', '"{', '}"','\''), '', $json_string);
        $json_string = str_replace('null', 'No payload', $json_string);
        return '<div style="white-space: pre-wrap">'.$json_string.'</div>';
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
        // If data is empty, remove record
        //if( ! $data)
        //{
        //	$this->delete();
        //	return;
        //}
        
        // Translate profile strings to db fields
        $this->deleteWhere('serial_number=?', $this->serial_number);
        $translate = array(
            'ProfileUUID = ' => 'profile_uuid',
            'ProfileName = ' => 'profile_name',
            'ProfileRemovalDisallowed = ' => 'profile_removal_allowed',
            'PayloadName = ' => 'payload_name',
            'PayloadDisplayName = ' => 'payload_display',
            'PayloadData = ' => 'payload_data');
        foreach (explode("\n", $data) as $line) {
           // Translate standard entries
            foreach ($translate as $search => $field) {
              //the separator is what triggers the save for each display
              //making sure we have a valid s/n.
                if ((strpos($line, '---------') === 0) && ($this->profile_uuid)) {
                    $this->id = 0;
                    $this->save(); //the actual save
                    $this->profile_uuid = null; //unset the display s/n to avoid writing twice if multiple separators are passed
                    break;
                } elseif (strpos($line, $search) === 0) { //else if not separator and matches
                    $value = substr($line, strlen($search)); //get the current value
                    $this->$field = $value;
                    break;
                }
            } //end foreach translate

         //timestamp added by the server
            $this->timestamp = time();
        } //end foreach explode lines
    }
}
