<?php

use CFPropertyList\CFPropertyList;

class Munkireportinfo_model extends \Model 
{

        public function __construct($serial='')
        {
                parent::__construct('id', 'munkireportinfo'); //primary key, tablename
                $this->rs['id'] = 0;
                $this->rs['serial_number'] = $serial;
                $this->rs['version'] = 0;
                $this->rs['baseurl'] = '';
                $this->rs['passphrase'] = '';
                $this->rs['reportitems'] = '';

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
            
            // Process incoming MunkiReport.plist
            $parser = new CFPropertyList();
            $parser->parse($data);
            
            $plist = array_change_key_case($parser->toArray(), CASE_LOWER);

            // Convert version to int
            if (isset($plist['version'])) {
                $digits = explode('.', $plist['version']);
                $mult = 10000;
                $plist['version'] = 0;
                foreach ($digits as $digit) {
                    $plist['version'] += $digit * $mult;
                    $mult = $mult / 100;
                }
            }
            
            // Set default version value
            if(empty($plist['version'])){
                $plist['version'] = null;
            }
                
            foreach (array('baseurl', 'passphrase', 'version', 'reportitems') as $item) {
                if (isset($plist[$item])) {
                    if ($item == 'reportitems'){
                        
                        $modulelist = array_keys($plist["reportitems"]);
                        sort($modulelist);
                        $modulelistproper = implode(", ",$modulelist);
                        $this->$item = $modulelistproper;                  
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
