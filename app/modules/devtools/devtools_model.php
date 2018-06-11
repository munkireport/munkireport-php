<?php

use CFPropertyList\CFPropertyList;

class Devtools_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'devtools'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['cli_tools'] = '';
		$this->rs['dashcode_version'] = '';
		$this->rs['devtools_path'] = ''; $this->rt['devtools_path'] = 'TEXT';
		$this->rs['devtools_version'] = '';
		$this->rs['instruments_version'] = '';
		$this->rs['interface_builder_version'] = '';
		$this->rs['ios_sdks'] = ''; $this->rt['ios_sdks'] = 'TEXT';
		$this->rs['ios_simulator_sdks'] = ''; $this->rt['ios_simulator_sdks'] = 'TEXT';
		$this->rs['macos_sdks'] = ''; $this->rt['macos_sdks'] = 'TEXT';
		$this->rs['tvos_sdks'] = ''; $this->rt['tvos_sdks'] = 'TEXT';
		$this->rs['tvos_simulator_sdks'] = ''; $this->rt['tvos_simulator_sdks'] = 'TEXT';
		$this->rs['watchos_sdks'] = ''; $this->rt['watchos_sdks'] = 'TEXT';
		$this->rs['watchos_simulator_sdks'] = ''; $this->rt['watchos_simulator_sdks'] = 'TEXT';
		$this->rs['xcode_version'] = '';
		$this->rs['xquartz'] = '';

		if ($serial) {
			$this->retrieve_record($serial);
		}
        
		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------

    
	/**
	 * Process data sent by postflight
	 *
	 * @param plist data
	 * @author tuxudo
	 **/
	function process($plist)
	{
		
		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}

		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$myList = $parser->toArray();
        		
		$typeList = array(
			'cli_tools' => '',
			'xquartz' => '',
			'devtools_path' => '',
			'devtools_version' => '',
			'instruments_version' => '',
			'xcode_version' => '',
			'dashcode_version' => '',
			'interface_builder_version' => '',
			'ios_sdks' => '',
			'ios_simulator_sdks' => '',
			'macos_sdks' => '',
			'tvos_sdks' => '',
			'tvos_simulator_sdks' => '',
			'watchos_sdks' => '',
			'watchos_simulator_sdks' => '',
		);
		
		foreach ($myList as $tool) {
            
			foreach ($typeList as $key => $value) {
				$this->rs[$key] = $value;
				if(array_key_exists($key, $tool))
				{
					$this->rs[$key] = $tool[$key];
				}
			}
			
            // Trim off the ending comma and space
            $this->rs['ios_sdks'] = substr_replace($this->rs['ios_sdks'] ,"",-2);
            $this->rs['ios_simulator_sdks'] = substr_replace($this->rs['ios_simulator_sdks'] ,"",-2);
            $this->rs['macos_sdks'] = substr_replace($this->rs['macos_sdks'] ,"",-2);
            $this->rs['tvos_sdks'] = substr_replace($this->rs['tvos_sdks'] ,"",-2);
            $this->rs['tvos_simulator_sdks'] = substr_replace($this->rs['tvos_simulator_sdks'] ,"",-2);
            $this->rs['watchos_sdks'] = substr_replace($this->rs['watchos_sdks'] ,"",-2);
            $this->rs['watchos_simulator_sdks'] = substr_replace($this->rs['watchos_simulator_sdks'] ,"",-2);

            //Save the data (also save the whales)
            $this->save();
		}
	}
}
