<?php

use CFPropertyList\CFPropertyList;

class Displays_info_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'displays'); // Primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; // Serial number of the computer
        $this->rs['type'] = 0; // Built-in = 0 ; External = 1
        $this->rs['display_serial'] = ''; // Serial number of the display, if any
        $this->rs['vendor'] = ''; // Vendor for the display
        $this->rs['model'] = ''; // Model of the display
        $this->rs['manufactured'] = ''; // Aprox. date when it was built
        $this->rs['native'] = ''; // Native resolution
        $this->rs['timestamp'] = 0; // Unix time when the report was uploaded
        $this->rs['ui_resolution'] = '';
        $this->rs['current_resolution'] = '';
        $this->rs['color_depth'] = '';
        $this->rs['display_type'] = '';
        $this->rs['main_display'] = 0; // Boolean
        $this->rs['mirror'] = 0; // Boolean
        $this->rs['mirror_status'] = '';
        $this->rs['online'] = 0; // Boolean
        $this->rs['interlaced'] = 0; // Boolean
        $this->rs['rotation_supported'] = 0; // Boolean
        $this->rs['television'] = 0; // Boolean
        $this->rs['display_asleep'] = 0; // Boolean
        $this->rs['ambient_brightness'] = 0; // Boolean
        $this->rs['automatic_graphics_switching'] = 0; // Boolean
        $this->rs['retina'] = 0; // Boolean
        $this->rs['edr_enabled'] = 0; // Boolean
        $this->rs['edr_limit'] = 0;
        $this->rs['edr_supported'] = 0; // Boolean
        $this->rs['connection_type'] = '';
        $this->rs['dp_dpcd_version'] = '';
        $this->rs['dp_current_bandwidth'] = '';
        $this->rs['dp_current_lanes'] = 0;
        $this->rs['dp_current_spread'] = '';
        $this->rs['dp_hdcp_capability'] = 0; // Boolean
        $this->rs['dp_max_bandwidth'] = '';
        $this->rs['dp_max_lanes'] = 0;
        $this->rs['dp_max_spread'] = '';
        $this->rs['virtual_device'] = 0; // Boolean
        $this->rs['dynamic_range'] = '';
        $this->rs['dp_adapter_firmware_version'] = ''; 

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    } // End construct
    
    /**
     * Get count of displays
     *
     *
     * @param int $type type 1 is external, type 0 is internal
     **/
    public function get_count($type)
    {
        $sql = "SELECT COUNT(CASE WHEN type=? AND AND (virtual_device=0 OR virtual_device IS NULL) THEN 1 END) AS total
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
     * @author Noel B.A., reworked by tuxudo
     **/
    public function process($data)
    {
        // If data is empty, throw error
        if (! $data) {
            print_r("Error Processing Displays Info Module Request: No data found");
        } else if (substr( $data, 0, 30 ) != '<?xml version="1.0" encoding="' ) { // Else if old style txt format, process with old txt based handler
            
            // Translate array used to match data to db fields
            $translate = array('Type = ' => 'type',
                              'Serial = ' => 'display_serial',
                              'Vendor = ' => 'vendor',
                              'Model = ' => 'model',
                              'Manufactured = ' => 'manufactured',
                              'Native = ' => 'native');

            // If we didn't specify in the config that we like history then
            // We nuke any data we had with this computer's serial number
            if (! conf('keep_previous_displays')) {
                $this->deleteWhere('serial_number=?', $this->serial_number);
                $this->display_serial = null; // Get rid of any serial number that was left in memory
            }
            
            // Parse data
            foreach (explode("\n", $data) as $line) {
              // Translate standard entries
                foreach ($translate as $search => $field) {
                  // The separator is what triggers the save for each display
                  // Making sure we have a valid serial number.
                    if ((strpos($line, '----------') === 0) && ($this->display_serial)) {
                      // If we have not nuked the records, do a selective delete
                        if (conf('keep_previous_displays')) {
                            $this->deleteWhere('serial_number=? AND display_serial=?', array($this->serial_number, $this->display_serial));
                        }
                      // Get a new id
                        $this->id = 0;
                        $this->save(); // The actual save
                        $this->display_serial = null; // Unset the display serial number to avoid writing twice if multiple separators are passed
                        break;
                    } else if (strpos($line, $search) === 0) { // Else if not separator and matches
                        $value = substr($line, strlen($search)); // Get the current value
                        // Use bool for Type
                        if (strpos($value, 'Internal') === 0) {
                            $this->$field = 0;
                            break;
                        } else if (strpos($value, 'External') === 0) {
                            $this->$field = 1;
                            break;
                        }

                        $this->$field = $value;
                        break;
                    }
                } // End foreach translate

                // Timestamp added by the server
                $this->timestamp = time();
            } // End foreach explode lines
            
        } else { // Else process with new XML handler
            
            // If we didn't specify in the config that we like history then
            // We nuke any data we had with this computer's serial number
            if (! conf('keep_previous_displays')) {
                $this->deleteWhere('serial_number=?', $this->serial_number);
                $this->display_serial = null; // Get rid of any serial number that was left in memory
            }
            
            // Process incoming displays.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();
            
            foreach ($plist as $display) {
                
                // Process each display
                foreach (array('type','display_serial','vendor','model','manufactured','native','ui_resolution','current_resolution','color_depth','display_type','main_display','mirror','mirror_status','online','interlaced','rotation_supported','television','display_asleep','ambient_brightness','automatic_graphics_switching','retina','edr_enabled','edr_limit','edr_supported','connection_type','dp_dpcd_version','dp_current_bandwidth','dp_current_lanes','dp_current_spread','dp_hdcp_capability','dp_max_bandwidth','dp_max_lanes','dp_max_spread','virtual_device','dynamic_range','dp_adapter_firmware_version') as $item) {
                    // If key does not exist in $display, null it
                    if ( ! array_key_exists($item, $display) && $item == 'display_serial') {
                        // For legacy purposes, display serial number cannot be null
                        $this->$item = "n/a";
                    } else if ( ! array_key_exists($item, $display)) {
                        $this->$item = null;
                    // Set the db fields to be the same as those for the display
                    } else {
                        $this->$item = $display[$item];
                    }
                }
                
                // If we have not nuked the records, do a selective delete
                if (conf('keep_previous_displays')) {
                    // Selectively delete display by matching display serial number, vendor, and native resolution
                    $this->deleteWhere('serial_number=? AND display_serial=? AND native=? AND vendor=?', array($this->serial_number, $this->display_serial, $this->native, $this->vendor));
                }
                
                // Timestamp added by the server
                $this->timestamp = time();
                
                // Check if we are to save virtual displays, default is yes
                if (conf('show_virtual_displays')) {
                    // Delete previous virtual displays if we are to keep them, because they can easily change and duplicate and we only want the latest ones
                    $this->deleteWhere('serial_number=? AND virtual_device=?', array($this->serial_number, 1));
                    
                    // Only save the display it has a valid native resolution
                    if (array_key_exists('native', $display)){
                        // Save the data
                        $this->id = '';
                        $this->save();
                    }
                } else {
                    // Only save the display it has a valid native resolution and is not a virtual display
                    if (array_key_exists('native', $display) && $display['virtual_device'] != 1){
                        // Save the data
                        $this->id = '';
                        $this->save();
                    }
                }
            }
        }
    } // Process function end
} // Displays_info_model end
