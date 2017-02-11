<?php
class Softwareupdate_model extends Model 
{
        public function __construct($serial='')
        {
                parent::__construct('id', 'softwareupdate'); //primary key, tablename
                $this->rs['id'] = 0;
                $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
                $this->rs['automaticcheckenabled'] = 0; $this->rt['automaticcheckenabled'] = 'INT';
                $this->rs['automaticdownload'] = 0; $this->rt['automaticdownload'] = 'INT';
                $this->rs['configdatainstall'] = 0; $this->rt['configdatainstall'] = 'INT';
                $this->rs['criticalupdateinstall'] = 0; $this->rt['criticalupdateinstall'] = 'INT';
                $this->rs['lastattemptsystemversion'] = '';
                $this->rs['lastbackgroundccdsuccessfuldate'] = '';
                $this->rs['lastbackgroundsuccessfuldate'] = '';
                $this->rs['lastfullsuccessfuldate'] = '';
                $this->rs['lastrecommendedupdatesavailable'] = 0; $this->rt['lastrecommendedupdatesavailable'] = 'INT';
                $this->rs['lastresultcode'] = 0; $this->rt['lastresultcode'] = 'INT';
                $this->rs['lastsessionsuccessful'] = 0; $this->rt['lastsessionsuccessful'] = 'INT';
                $this->rs['lastsuccessfuldate'] = '';
                $this->rs['lastupdatesavailable'] = 0; $this->rt['lastupdatesavailable'] = 'INT';
                $this->rs['skiplocalcdn'] = 0; $this->rt['skiplocalcdn'] = 'INT';
                $this->rs['recommendedupdates'] = '';
                $this->rs['mrxprotect'] = '';
                $this->rs['catalogurl'] = '';
                $this->rs['inactiveupdates'] = '';

                // Schema version, increment when creating a db migration
                $this->schema_version = 0;
                
                //indexes to optimize queries
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
                $this->create_table();
                
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
            require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
            $parser = new CFPropertyList();
            $parser->parse($data);
            
            $plist = array_change_key_case($parser->toArray(), CASE_LOWER);
                        
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
                        
                        var_dump($plist[$item]);
                        
                        if ($plist[$item] == "1"){
                           $this->$item = 1;
                                                    var_dump($this->$item);
                        }
                        else if ($plist[$item] == "0"){
                            $this->$item = 0;
                                                    var_dump($this->$item);
                        }
                        else {
                            $this->$item = -1;
                                                    var_dump($this->$item);
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
