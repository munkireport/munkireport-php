<?php
class kb_mouse_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'kb_mouse'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		//0 means not connected
		$this->rs['ext_kb_st'] = 0; $this->rt['ext_kb_st'] = 'INTEGER';
		$this->rs['ext_kb_nm'] = ''; // string
		$this->rs['ext_mouse_st'] = 0; $this->rt['ext_mouse_st'] = 'INTEGER';
		$this->rs['ext_mouse_nm'] = ''; // string
		$this->rs['int_tp_st'] = 0; $this->rt['int_tp_st'] = 'INTEGER';
		$this->rs['int_tp_nm'] = ''; // string

		// Schema version, increment when creating a db migration
		$this->schema_version = 1;

		// Create table if it does not exist
		$this->create_table();

		if ($serial) {
			$this->retrieve_record($serial);		
			$this->serial = $serial;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author miqviq
	 **/
	function process($data)
	{
		// Translate strings to db fields
        $translate = array(
        	'EXT_KEYBOARD_STATUS:' => 'ext_kb_st',
        	'EXT_KEYBOARD_NAME:' => 'ext_kb_nm',
        	'EXT_MOUSE_STATUS:' => 'ext_mouse_st',
        	'EXT_MOUSE_NAME:' => 'ext_mouse_nm',
        	'INT_TRPAD_STATUS:' => 'int_tp_st',
        	'INT_TRPAD_NAME:' => 'int_tp_nm');
			
		//clear any previous data we had
		foreach($translate as $search => $field) {
			$this->$field = 0;
		}
		
		// Parse data
		foreach(explode("\n", $data) as $line) {
		    // Translate standard entries
			foreach($translate as $search => $field) {

			    if(strpos($line, $search) === 0) {

				    $value = substr($line, strlen($search));

				    $this->$field = $value;
				    break;
			    }
			}

		} //end foreach explode lines
		$this->save();
	}
}
