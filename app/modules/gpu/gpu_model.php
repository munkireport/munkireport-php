<?php
class Gpu_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'gpu'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['model'] = '';
		$this->rs['vendor'] = '';
		$this->rs['vram'] = '';
		$this->rs['pcie_width'] = '';
		$this->rs['slot_name'] = '';
		$this->rs['device_id'] = '';
		$this->rs['gmux_version'] = '';
		$this->rs['efi_version'] = '';
		$this->rs['revision_id'] = '';
		$this->rs['rom_revision'] = '';

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('model');
		$this->idx[] = array('vendor');
		$this->idx[] = array('vram');
		$this->idx[] = array('pcie_width');
		$this->idx[] = array('slot_name');
		$this->idx[] = array('device_id');
		$this->idx[] = array('gmux_version');
		$this->idx[] = array('efi_version');
		$this->idx[] = array('revision_id');
		$this->idx[] = array('rom_revision');
        
		// Create table if it does not exist
		$this->create_table();

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

    
     /**
     * Get GPU models for widget
     *
     **/
     public function get_gpu_models()
     {
        $out = array();
        $sql = "SELECT COUNT(CASE WHEN model <> '' AND model IS NOT NULL THEN 1 END) AS count, model 
                FROM gpu
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY model
                ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->model = $obj->model ? $obj->model : 'Unknown';
                $out[] = $obj;
            }
        }
        return $out;
     }
    
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
		
		// Delete previous set        
		$this->deleteWhere('serial_number=?', $this->serial_number);

		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();
        		
		$typeList = array(
			'model' => '',
			'vendor' => '',
			'vram' => '',
			'vram_shared' => '',
			'pcie_width' => '',
			'slot_name' => '',
			'device_id' => '',
			'gmux_version' => '',
			'efi_version' => '',
			'revision_id' => '',
			'rom_revision' => '',
		);
		
		foreach ($myList as $device) {
			// Check if we have a model
			if( ! array_key_exists("model", $device)){
				continue;
			}
            
			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $device))
				{
					$this->rs[$key] = $device[$key];
				}
			}
			
            // Set VRAM from shared VRAM
			if( array_key_exists("vram_shared", $device)){
				$this->rs['vram'] = ($device['vram_shared']." (Shared)");
			}
            
			// Save the GPU
			$this->id = '';
			$this->save();
		}
	}
}
