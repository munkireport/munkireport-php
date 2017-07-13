<?php
class Usage_stats_model extends Model {

    function __construct($serial='')
	{
		parent::__construct('id', 'usage_stats'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['timestamp'] = 0; $this->rt['timestamp'] = 'BIGINT';
        $this->rs['thermal_pressure'] = '';
		$this->rs['backlight_max'] = 0;
		$this->rs['backlight_min'] = 0;
		$this->rs['backlight'] = 0;
		$this->rs['keyboard_backlight'] = 0;
		$this->rs['ibyte_rate'] = 0.0;
		$this->rs['ibytes'] = 0.0;
		$this->rs['ipacket_rate'] = 0.0;
		$this->rs['ipackets'] = 0.0;
		$this->rs['obyte_rate'] = 0.0;
		$this->rs['obytes'] = 0.0;
		$this->rs['opacket_rate'] = 0.0;
		$this->rs['opackets'] = 0.0;
		$this->rs['rbytes_per_s'] = 0.0;
		$this->rs['rops_per_s'] = 0.0;
		$this->rs['wbytes_per_s'] = 0.0;
		$this->rs['wops_per_s'] = 0.0;
		$this->rs['rbytes_diff'] = 0.0;
		$this->rs['rops_diff'] = 0.0;
		$this->rs['wbytes_diff'] = 0.0;
		$this->rs['wops_diff'] = 0.0;
		$this->rs['package_watts'] = 0.0;
		$this->rs['package_joules'] = 0.0;
		$this->rs['freq_hz'] = 0.0; // CPU
		$this->rs['freq_ratio'] = 0.0; // CPU
		$this->rs['gpu_name'] = '';
		$this->rs['gpu_freq_hz'] = 0.0;
		$this->rs['gpu_freq_mhz'] = 0.0;
		$this->rs['gpu_freq_ratio'] = 0.0;
		$this->rs['gpu_busy'] = 0.0;

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('timestamp');
		$this->idx[] = array('backlight_max');
		$this->idx[] = array('backlight_min');
		$this->idx[] = array('backlight');
		$this->idx[] = array('keyboard_backlight');
		$this->idx[] = array('ibyte_rate');
		$this->idx[] = array('ibytes');
		$this->idx[] = array('ipacket_rate');
		$this->idx[] = array('ipackets');
		$this->idx[] = array('obyte_rate');
		$this->idx[] = array('obytes');
		$this->idx[] = array('opacket_rate');
		$this->idx[] = array('opackets');
		$this->idx[] = array('rbytes_per_s');
		$this->idx[] = array('rops_per_s');
		$this->idx[] = array('wbytes_per_s');
		$this->idx[] = array('wops_per_s');
		$this->idx[] = array('rbytes_diff');
		$this->idx[] = array('rops_diff');
		$this->idx[] = array('wbytes_diff');
		$this->idx[] = array('wops_diff');
		$this->idx[] = array('thermal_pressure');
		$this->idx[] = array('package_watts');
		$this->idx[] = array('package_joules');
		$this->idx[] = array('freq_hz');
		$this->idx[] = array('freq_ratio');
		$this->idx[] = array('gpu_name');
		$this->idx[] = array('gpu_freq_hz');
		$this->idx[] = array('gpu_freq_mhz');
		$this->idx[] = array('gpu_freq_ratio');
		$this->idx[] = array('gpu_busy');
        
		// Create table if it does not exist
		$this->create_table();

        if ($serial) {
            $this->retrieve_record($serial);
        }
        
		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

    
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
		
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();
		
        // Save the timestamp
        $this->rs['timestamp'] = $myList['timestamp'];
        
        // Check for thermal pressure
        if( array_key_exists("thermal_pressure", $myList)){
            $this->rs['thermal_pressure'] = ($myList['thermal_pressure']);
        } else {
           $this->rs['thermal_pressure'] = null; 
        }
        
        // Check for keyboard backlight
        if( array_key_exists("keyboard_backlight", $myList)){
            $this->rs['keyboard_backlight'] = ($myList['keyboard_backlight']['value']);
        } else {
           $this->rs['keyboard_backlight'] = null; 
        }
        
        // Check for backlight
        if( array_key_exists("backlight", $myList)){
            
            // Check for max
            if( array_key_exists("max", $myList['backlight'])){
                $this->rs['backlight_max'] = ($myList['backlight']['max']);
            } else {
                $this->rs['backlight_max'] = null;
            }
            
            // Check for min
            if( array_key_exists("max", $myList['backlight'])){
                $this->rs['backlight_min'] = ($myList['backlight']['min']);
            } else {
                $this->rs['backlight_min'] = null;
            }
            
            // Check for value
            if( array_key_exists("value", $myList['backlight'])){
                $this->rs['backlight'] = ($myList['backlight']['value']);
            } else {
                $this->rs['backlight'] = null;
            }
        } else {
            $this->rs['backlight_max'] = null;
            $this->rs['backlight_min'] = null;
            $this->rs['backlight'] = null;
        }
        
        // Check for disk
        if( array_key_exists("disk", $myList)){
            $this->rs['rbytes_diff'] = ($myList['disk']['rbytes_diff']);
            $this->rs['rbytes_per_s'] = ($myList['disk']['rbytes_per_s']);
            $this->rs['rops_diff'] = ($myList['disk']['rops_diff']);
            $this->rs['rops_per_s'] = ($myList['disk']['rops_per_s']);
            $this->rs['wbytes_diff'] = ($myList['disk']['wbytes_diff']);
            $this->rs['wbytes_per_s'] = ($myList['disk']['wbytes_per_s']);
            $this->rs['wops_diff'] = ($myList['disk']['wops_diff']);
            $this->rs['wops_per_s'] = ($myList['disk']['wops_per_s']);
        } else {
            $this->rs['rbytes_diff'] = null;
            $this->rs['rbytes_per_s'] = null;
            $this->rs['rops_diff'] = null;
            $this->rs['rops_per_s'] = null;
            $this->rs['wbytes_diff'] = null;
            $this->rs['wbytes_per_s'] = null;
            $this->rs['wops_diff'] = null;
            $this->rs['wops_per_s'] = null;
        }
        
        // Check for network
        if( array_key_exists("network", $myList)){
            $this->rs['ibyte_rate'] = ($myList['network']['ibyte_rate']);
            $this->rs['ibytes'] = ($myList['network']['ibytes']);
            $this->rs['ipacket_rate'] = ($myList['network']['ipacket_rate']);
            $this->rs['ipackets'] = ($myList['network']['ipackets']);
            $this->rs['obyte_rate'] = ($myList['network']['obyte_rate']);
            $this->rs['obytes'] = ($myList['network']['obytes']);
            $this->rs['opacket_rate'] = ($myList['network']['opacket_rate']);
            $this->rs['opackets'] = ($myList['network']['opackets']);
        } else {
            $this->rs['ibyte_rate'] = null;
            $this->rs['ibytes'] = null;
            $this->rs['ipacket_rate'] = null;
            $this->rs['ipackets'] = null;
            $this->rs['obyte_rate'] = null;
            $this->rs['obytes'] = null;
            $this->rs['opacket_rate'] = null;
            $this->rs['opackets'] = null;
        }
        
        // Check for GPU
        if( array_key_exists("GPU", $myList)){
            
            // Check for gpu_name
            if( array_key_exists("name", $myList['GPU'][0])){
                $this->rs['gpu_name'] = ($myList['GPU'][0]['name']);
            } else {
                $this->rs['gpu_name'] = null;
            } 
            
            // Check for freq_hz
            if( array_key_exists("freq_hz", $myList['GPU'][0])){
                $this->rs['gpu_freq_hz'] = ($myList['GPU'][0]['freq_hz']);
            } else {
                $this->rs['gpu_freq_hz'] = null;
            } 
            
            // Check for freq_mhz
            if( array_key_exists("freq_mhz", $myList['GPU'][0])){
                $this->rs['gpu_freq_mhz'] = ($myList['GPU'][0]['freq_mhz']);
            } else {
                $this->rs['gpu_freq_mhz'] = null;
            } 
            
            // Check for freq_ratio
            if( array_key_exists("freq_ratio", $myList['GPU'][0])){
                $this->rs['gpu_freq_ratio'] = ($myList['GPU'][0]['freq_ratio']);
            } else {
                $this->rs['gpu_freq_ratio'] = null;
            } 
            
            // Check for misc_counters
            if( array_key_exists("misc_counters", $myList['GPU'][0])){
                // Check for GPU Busy
                if( array_key_exists("GPU Busy", $myList['GPU'][0]['misc_counters'])){
                    $this->rs['gpu_busy'] = ($myList['GPU'][0]['misc_counters']['GPU Busy']);
                } else {
                    $this->rs['gpu_busy'] = null;
                }
            } else {
                $this->rs['gpu_busy'] = null;
            }
        } else {
            $this->rs['gpu_name'] = null;
            $this->rs['gpu_freq_hz'] = null;
            $this->rs['gpu_freq_mhz'] = null;
            $this->rs['gpu_freq_ratio'] = null;
            $this->rs['gpu_busy'] = null;

        }
        
        // Check for processor
        if( array_key_exists("processor", $myList)){
            
            // Check for freq_hz
            if( array_key_exists("freq_hz", $myList['processor'])){
                $this->rs['freq_hz'] = ($myList['processor']['freq_hz']);
            } else {
                $this->rs['freq_hz'] = null;
            }   
            
            // Check for freq_ratio
            if( array_key_exists("freq_ratio", $myList['processor'])){
                $this->rs['freq_ratio'] = ($myList['processor']['freq_ratio']);
            } else {
                $this->rs['freq_ratio'] = null;
            }
            
            // Check for package_joules
            if( array_key_exists("package_joules", $myList['processor'])){
                $this->rs['package_joules'] = ($myList['processor']['package_joules']);
            } else {
                $this->rs['package_joules'] = null;
            }
            
            // Check for package_watts
            if( array_key_exists("package_watts", $myList['processor'])){
                $this->rs['package_watts'] = ($myList['processor']['package_watts']);
            } else {
                $this->rs['package_watts'] = null;
            }
        } else {
            $this->rs['freq_hz'] = null;
            $this->rs['freq_ratio'] = null;
            $this->rs['package_joules'] = null;
            $this->rs['package_watts'] = null;
        }
        
        // Fix Intel GPU name
        $this->rs['gpu_name'] = str_replace("IntelIG","Intel iGPU",$this->rs['gpu_name']);
            
        // Save the data 
        $this->save();
	}
}