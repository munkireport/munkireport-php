<?php

class Displays_info_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'displays'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['type'] = 0; // Built-in = 0 ; External =1
        $this->rs['display_serial'] = ''; // Serial num of the display, if any
        $this->rs['serial_number'] = $serial; // Serial num of the computer
        $this->rs['vendor'] = ''; // Vendor for the display
        $this->rs['model'] = ''; // Model of the display
        $this->rs['manufactured'] = ''; // Aprox. date when it was built
        $this->rs['native'] = ''; // Native resolution
        $this->rs['timestamp'] = 0; // Unix time when the report was uploaded

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    } //end construct
    
    /**
     * Get count of  displays
     *
     *
     * @param int $type type 1 is external, type 0 is internal
     **/
    public function get_count($type)
    {
        $sql = "SELECT COUNT(CASE WHEN type=? THEN 1 END) AS total
                    FROM displays
                    LEFT JOIN reportdata USING (serial_number)
                    ".get_machine_group_filter();
        return current($this->query($sql, $type));
    }


// ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author Noel B.A.
     **/
    public function process($data)
    {

      // translate array used to match data to db fields
        $translate = array('Type = ' => 'type',
                          'Serial = ' => 'display_serial',
                          'Vendor = ' => 'vendor',
                          'Model = ' => 'model',
                          'Manufactured = ' => 'manufactured',
                          'Native = ' => 'native');

      // if we didn't specify in the config that we like history then
      // we nuke any data we had with this computer's s/n
        if (! conf('keep_previous_displays')) {
            $this->deleteWhere('serial_number=?', $this->serial_number);
            $this->display_serial = null; //get rid of any s/n that was left in memory
        }
      // Parse data
        foreach (explode("\n", $data) as $line) {
          // Translate standard entries
            foreach ($translate as $search => $field) {
              //the separator is what triggers the save for each display
              //making sure we have a valid s/n.
                if ((strpos($line, '----------') === 0) && ($this->display_serial)) {
                  //if we have not nuked the records, do a selective delete
                    if (conf('keep_previous_displays')) {
                        $this->deleteWhere('serial_number=? AND display_serial=?', array($this->serial_number, $this->display_serial));
                    }
                  //get a new id
                    $this->id = 0;
                    $this->save(); //the actual save
                    $this->display_serial = null; //unset the display s/n to avoid writing twice if multiple separators are passed
                    break;
                } elseif (strpos($line, $search) === 0) { //else if not separator and matches
                    $value = substr($line, strlen($search)); //get the current value
                  // use bool for Type
                    if (strpos($value, 'Internal') === 0) {
                        $this->$field = 0;
                        break;
                    } elseif (strpos($value, 'External') === 0) {
                        $this->$field = 1;
                        break;
                    }

                    $this->$field = $value;
                    break;
                }
            } //end foreach translate

        //timestamp added by the server
            $this->timestamp = time();
        } //end foreach explode lines
    } //process function end
} // Displays_info_model end
