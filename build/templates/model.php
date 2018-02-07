<?php
class MODULE_model extends \Model {

    protected $restricted;

	function __construct($serial='')
	{
		parent::__construct('id', 'MODULE'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['item1'] = '';
        $this->rs['item2'] = 0;

        // Array with fields that can't be set by process
        $this->restricted = array('id', 'serial_number');

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Create table if it does not exist
		//$this->create_table();

		if ($serial)
			$this->retrieveOne('serial_number=?', $serial);

		$this->serial = $serial;

	}

	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 *
	 **/
	function process($data)
	{

        // This array will hold fields that can be populated
        $fillable = array();

		//clear any previous data we had
		foreach($this->rs as $field => $value) {
			if( ! in_array($field, $this->restricted)){
                $fillable[] = $field;
                $this->$field = '';
            }
		}
		// Parse data
        $sep = ' = ';
		foreach(explode("\n", $data) as $line) {
            echo $line;
            list($key, $val) = explode($sep, $line);

            if( in_array($key, $fillable)){
                $this->$key = $val;
            }

		} //end foreach explode lines

		$this->save();
	}
}
