<?php

use CFPropertyList\CFPropertyList;

class Ibridge_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'ibridge'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['boot_uuid'] = '';
		$this->rs['build'] = '';
		$this->rs['model_identifier'] = '';
		$this->rs['model_name'] = '';
		$this->rs['ibridge_serial_number'] = '';
		$this->rs['marketing_name'] = '';
		
		if ($serial) {
			$this->retrieve_record($serial);
		}
        
		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

    /**
     * Get iBridge models for widget
     *
     **/
     public function get_ibridge()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN model_name <> '' AND model_name IS NOT NULL THEN 1 END) AS count, model_name 
                FROM ibridge
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY model_name
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->model_name = $obj->model_name ? $obj->model_name : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }
    
    
	/**
	 * Process data sent by postflight
	 *
	 * @param plist data
	 * @author tuxudo
	 **/
	function process($plist)
	{
        // If plist is empty, echo out error
        if (! $plist) {
			echo ("Error Processing iBridge module: No data found");
		} else { 

            $parser = new CFPropertyList();
            $parser->parse($plist, CFPropertyList::FORMAT_XML);
            $myList = $parser->toArray();

            $typeList = array(
                'boot_uuid' => '',
                'build' => '',
                'model_identifier' => '',
                'model_name' => '',
                'ibridge_serial_number' => '',
                'marketing_name' => ''
            );

            foreach ($myList as $ibridge) {
                foreach ($typeList as $key => $value) {
                    $this->rs[$key] = $value;
                    if(array_key_exists($key, $ibridge))
                    {
                        $this->rs[$key] = $ibridge[$key];
                    }
                }

                // Resolve marketing name
                // Arrays will have to be updated to include new T chips are they released
                // Because Apple doesn't use the same names for the iMac Pro and Macbook Pro
                if ( $this->rs['model_identifier'] == '') {
                    // Macbook Pro
                    $this->rs['model_identifier'] = str_replace(array('Apple T1 chip','Apple T2 chip','Apple T3 chip'), array('iBridge1,1','iBridge2,1','iBridge3,1'), $this->rs['model_name']);
                    $this->rs['marketing_name'] = str_replace(array('Apple',' ','chip'), array('','',''), $this->rs['model_name']);
                } else {
                    // iMac Pro
                    $model_id_array = explode(",",$this->rs['model_identifier']);
                    $this->rs['model_name'] = str_replace('iBridge', 'Apple T', $model_id_array[0])." chip";
                    $this->rs['marketing_name'] = str_replace("iBridge", "T", $model_id_array[0]);
                }
                
                //Save the data (and save London Bridge from falling down!)
                $this->save();
            }
		}
	}
}
