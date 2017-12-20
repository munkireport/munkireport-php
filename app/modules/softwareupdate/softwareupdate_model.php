<?php

use CFPropertyList\CFPropertyList;

class Softwareupdate_model extends \Model 
{
        public function __construct($serial='')
        {
                parent::__construct('id', 'softwareupdate'); //primary key, tablename
                $this->rs['id'] = 0;
                $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
                $this->rs['automaticcheckenabled'] = 0;
                $this->rs['automaticdownload'] = 0;
                $this->rs['configdatainstall'] = 0;
                $this->rs['criticalupdateinstall'] = 0;
                $this->rs['lastattemptsystemversion'] = '';
                $this->rs['lastbackgroundccdsuccessfuldate'] = '';
                $this->rs['lastbackgroundsuccessfuldate'] = '';
                $this->rs['lastfullsuccessfuldate'] = '';
                $this->rs['lastrecommendedupdatesavailable'] = 0;
                $this->rs['lastresultcode'] = 0;
                $this->rs['lastsessionsuccessful'] = 0;
                $this->rs['lastsuccessfuldate'] = '';
                $this->rs['lastupdatesavailable'] = 0;
                $this->rs['skiplocalcdn'] = 0;
                $this->rs['recommendedupdates'] = '';
                $this->rs['mrxprotect'] = '';
                $this->rs['catalogurl'] = '';
                $this->rs['inactiveupdates'] = '';

                // Schema version, increment when creating a db migration
                $this->schema_version = 0;
                
                // Indexes to optimize queries
                $this->idx[] = array('automaticcheckenabled');
                $this->idx[] = array('automaticdownload');
                $this->idx[] = array('configdatainstall');
                $this->idx[] = array('criticalupdateinstall');
                $this->idx[] = array('lastattemptsystemversion');
                $this->idx[] = array('lastbackgroundccdsuccessfuldate');
                $this->idx[] = array('lastbackgroundsuccessfuldate');
                $this->idx[] = array('lastfullsuccessfuldate');
                $this->idx[] = array('lastrecommendedupdatesavailable');
                $this->idx[] = array('lastresultcode');
                $this->idx[] = array('lastsessionsuccessful');
                $this->idx[] = array('lastsuccessfuldate');
                $this->idx[] = array('lastupdatesavailable');
                $this->idx[] = array('skiplocalcdn');
                $this->idx[] = array('recommendedupdates');
                $this->idx[] = array('mrxprotect');
                $this->idx[] = array('inactiveupdates');
                
                // Create table if it does not exist
               //$this->create_table();
                
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
                throw new Exception("Error Processing Request: No data found", 1);
            }    
            
            // Process incoming com.apple.SoftwareUpdate.plist
            $parser = new CFPropertyList();
            $parser->parse($data);
            
            $plist = array_change_key_case($parser->toArray(), CASE_LOWER);
            
            // Fill in missing keys
            if( ! array_key_exists("automaticcheckenabled",$plist)){
                $plist["automaticcheckenabled"] = -1;
            }
            if( ! array_key_exists("automaticdownload",$plist)){
                $plist["automaticdownload"] = -1;
            }
            if( ! array_key_exists("configdatainstall",$plist)){
                $plist["configdatainstall"] = -1;
            }
            if( ! array_key_exists("criticalupdateinstall",$plist)){
                $plist["criticalupdateinstall"] = -1;
            }
            if( ! array_key_exists("lastsessionsuccessful",$plist)){
                $plist["lastsessionsuccessful"] = -1;
            }
            if( ! array_key_exists("skiplocalcdn",$plist)){
                $plist["skiplocalcdn"] = -1;
            }
                        
            foreach (array('automaticcheckenabled', 'automaticdownload', 'configdatainstall', 'criticalupdateinstall','lastattemptsystemversion','lastbackgroundccdsuccessfuldate','lastbackgroundsuccessfuldate','lastfullsuccessfuldate','lastrecommendedupdatesavailable','lastresultcode','lastsessionsuccessful','lastsuccessfuldate','lastupdatesavailable','skiplocalcdn','recommendedupdates','mrxprotect','catalogurl','inactiveupdates') as $item) {
                if (isset($plist[$item])) {                    
                    if ($item == 'recommendedupdates'){
                                                
                        $recommendedupdateslist = array_column($plist["recommendedupdates"], 'Display Name');                        
                        sort($recommendedupdateslist);
                        $recommendedupdateslistproper = implode(", ",$recommendedupdateslist);
                        $this->$item = $recommendedupdateslistproper;
                        
                    } else if ($item == 'lastbackgroundccdsuccessfuldate' || $item == 'lastbackgroundsuccessfuldate' || $item == 'lastfullsuccessfuldate' || $item == 'lastsuccessfuldate'){ 
                        
                        $this->$item = date('Y-m-d H:i:s', $plist[$item]);
                        
                    } else if ($item == 'inactiveupdates'){ 
                        
                        $inactiveupdateslist = $plist["inactiveupdates"];                        
                        sort($inactiveupdateslist);
                        $inactiveupdateslistproper = implode(", ",$inactiveupdateslist);
                        $this->$item = $inactiveupdateslistproper;                        
                        
                    } else if ($item == 'automaticcheckenabled' || $item == 'automaticdownload' || $item == 'configdatainstall' || $item == 'criticalupdateinstall' || $item == 'lastsessionsuccessful' || $item == 'skiplocalcdn'){ 
                                                
                        if ($plist[$item] == "1"){
                           $this->$item = 1;
                        }
                        else if ($plist[$item] == "0"){
                            $this->$item = 0;
                        }
                        else {
                            $this->$item = -1;
                        }
                        
                    } else {    
                        $this->$item = $plist[$item];
                    }
                    
                } else {
                    $this->$item = '';
                }
            }
            
            // Save the data
            $this->save();  
        }
}