<?php

use CFPropertyList\CFPropertyList;

class Supported_os_model extends \Model
{    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'supported_os'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['current_os'] = 0;
        $this->rs['highest_supported'] = 0;
        $this->rs['machine_id'] = '';
        $this->rs['last_touch'] = 0; $this->rt['last_touch'] = 'BIGINT';
    }
    
    // ------------------------------------------------------------------------

    /**
     * Process method, is called by the client
     *
     * @return void
     * @author tuxudo
     **/
    public function process($data)
    {
        
        if ( ! $data){
            throw new Exception("Error Processing Request: No property list found", 1);
        }
        
        // Delete previous set        
        $this->deleteWhere('serial_number=?', $this->serial_number);
        
        $parser = new CFPropertyList();
        $parser->parse($data);     
        $plist = $parser->toArray();
        
        $most_current_os = "10.13.5"; //Update this as Apple releases new point updates
        
        if (strpos($plist['machine_id'], 'iMacPro') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num >= 11) {
                $plist['highest_supported'] = $most_current_os;
            }
        } else if (strpos($plist['machine_id'], 'iMac') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num >= 131) {
                $plist['highest_supported'] = $most_current_os;
            } else if ($model_num >= 101) {
                $plist['highest_supported'] = "10.13.6";
            } else if ($model_num >= 71) {
                $plist['highest_supported'] = "10.11.6";
            } else if ($model_num >= 51) {
                $plist['highest_supported'] = "10.7.5";
            } else {
                $plist['highest_supported'] = "10.6.8";
            }
        } else if (strpos($plist['machine_id'], 'Macmini') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num >= 61) {
                $plist['highest_supported'] = $most_current_os;
            } else if ($model_num >= 41) {
                $plist['highest_supported'] = "10.13.6";
            } else if ($model_num >= 31) {
                $plist['highest_supported'] = "10.11.6";
            } else if ($model_num >= 21) {
                $plist['highest_supported'] = "10.7.5";
            } else {
                $plist['highest_supported'] = "10.6.8";
            }
        } else if (strpos($plist['machine_id'], 'MacPro') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num >= 51) {
                $plist['highest_supported'] = $most_current_os;
            } else if ($model_num >= 31) {
                $plist['highest_supported'] = "10.11.6";
            } else {
                $plist['highest_supported'] = "10.7.5";
            }
        } else if (strpos($plist['machine_id'], 'MacBookPro') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num >= 91) {
                $plist['highest_supported'] = $most_current_os;
            } else if ($model_num >= 71) {
                $plist['highest_supported'] = "10.13.6";
            } else if ($model_num >= 31) {
                $plist['highest_supported'] = "10.11.6";
            } else if ($model_num >= 21) {
                $plist['highest_supported'] = "10.7.5";
            } else {
                $plist['highest_supported'] = "10.6.8";
            }        
        } else if (strpos($plist['machine_id'], 'MacBookAir') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num >= 51) {
                $plist['highest_supported'] = $most_current_os;
            } else if ($model_num >= 31) {
                $plist['highest_supported'] = "10.13.6";
            } else if ($model_num >= 21) {
                $plist['highest_supported'] = "10.11.6";
            } else {
                $plist['highest_supported'] = "10.7.5";
            } 
        } else if (strpos($plist['machine_id'], 'MacBook') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num >= 81) {
                $plist['highest_supported'] = $most_current_os;
            } else if ($model_num >= 61) {
                $plist['highest_supported'] = "10.13.6";
            } else if ($model_num >= 51) {
                $plist['highest_supported'] = "10.11.6";
            } else if ($model_num >= 21) {
                $plist['highest_supported'] = "10.7.5";
            } else {
                $plist['highest_supported'] = "10.6.8";
            }  
        } else if (strpos($plist['machine_id'], 'Xserve') !== false) {
            $model_num = preg_replace("/[^0-9]/", "", $plist['machine_id']);
            if ($model_num == 31) {
                $plist['highest_supported'] = "10.11.6";
            } else {
                $plist['highest_supported'] = "10.7.5";
            } 
        } else {
            $plist['highest_supported'] = "";
        }       
        
        // Convert highest_supported to int
        if (isset($plist['highest_supported'])) {
            $digits = explode('.', $plist['highest_supported']);
            $mult = 10000;
            $plist['highest_supported'] = 0;
            foreach ($digits as $digit) {
                $plist['highest_supported'] += $digit * $mult;
                $mult = $mult / 100;
            }
        }

        // Set default current_os value
        if(empty($plist['highest_supported'])){
            $plist['highest_supported'] = null;
        }
        
        // Convert current_os to int
        if (isset($plist['current_os'])) {
            $digits = explode('.', $plist['current_os']);
            $mult = 10000;
            $plist['current_os'] = 0;
            foreach ($digits as $digit) {
                $plist['current_os'] += $digit * $mult;
                $mult = $mult / 100;
            }
        }

        // Set default current_os value
        if(empty($plist['current_os'])){
            $plist['current_os'] = null;
        }
        
        $this->current_os = $plist['current_os'];
        $this->machine_id = $plist['machine_id'];
        $this->last_touch = $plist['last_touch'];
        $this->highest_supported = $plist['highest_supported'];
        
        // Save OS gibblets
        $this->save();
        
    } // end process()
}
