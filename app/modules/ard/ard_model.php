<?php
class Ard_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'ard'); //primary key, tablename
		$this->rs['id'] = 0;
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['Text1'] = '';
		$this->rs['Text2'] = '';
		$this->rs['Text3'] = '';
		$this->rs['Text4'] = '';

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('Text1');
		$this->idx[] = array('Text2');
		$this->idx[] = array('Text3');
		$this->idx[] = array('Text4');

		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
		{
			$this->retrieve_one('serial_number=?', $serial);
		}
		
		$this->serial = $serial;
		  
	}

	function process($data)
	{
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($data);
		
		$plist = $parser->toArray();

		foreach(array('Text1', 'Text2', 'Text3', 'Text4') AS $item)
		{
			if (isset($plist[$item]))
			{
				$this->$item = $plist[$item];
			}
			else
			{
				$this->$item = '';
			}
		}

		$this->save();
	}
}