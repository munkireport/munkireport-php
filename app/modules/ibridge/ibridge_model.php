<?php

use CFPropertyList\CFPropertyList;

class Ibridge_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'ibridge'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['ibridge_boot_uuid'] = '';
		$this->rs['ibridge_build'] = '';
		$this->rs['ibridge_model_identifier'] = '';
		$this->rs['ibridge_model_name'] = '';
		$this->rs['ibridge_serial_number'] = '';
		$this->rs['ibridge_marketing_name'] = '';
		
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
        $sql = "SELECT COUNT(CASE WHEN ibridge_model_name <> '' AND ibridge_model_name IS NOT NULL THEN 1 END) AS count, ibridge_model_name 
                FROM ibridge
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY ibridge_model_name
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->ibridge_model_name = $obj->ibridge_model_name ? $obj->ibridge_model_name : 'Unknown';
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
                'ibridge_boot_uuid' => '',
                'ibridge_build' => '',
                'ibridge_model_identifier' => '',
                'ibridge_model_name' => '',
                'ibridge_serial_number' => '',
                'ibridge_marketing_name' => ''
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
                if ( $this->rs['ibridge_model_identifier'] == '') {
                    // Macbook Pro
                    $this->rs['ibridge_model_identifier'] = str_replace(array('Apple T1 chip','Apple T2 chip','Apple T3 chip'), array('iBridge1,1','iBridge2,1','iBridge3,1'), $this->rs['ibridge_model_name']);
                    $this->rs['ibridge_marketing_name'] = str_replace(array('Apple',' ','chip'), array('','',''), $this->rs['ibridge_model_name']);
                } else {
                    // iMac Pro
                    $this->rs['ibridge_model_name'] = str_replace(array('iBridge1,1','iBridge2,1','iBridge3,1'), array('Apple T1 chip','Apple T2 chip','Apple T3 chip'), $this->rs['ibridge_model_identifier']);
                    $this->rs['ibridge_marketing_name'] = str_replace(array('iBridge1,1','iBridge2,1','iBridge3,1'), array('T1','T2','T3'), $this->rs['ibridge_model_identifier']);
                }
                
                //Save the data (and save London Bridge from falling down!)
                $this->save();
            }
		}
	}
}
