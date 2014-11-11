<?php

class Hash extends Model {
    
    function __construct($serial='', $name='')
    {
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial'] = '';
        $this->rs['name'] = '';
        $this->rs['hash'] = '';
        $this->rs['timestamp'] = time();

        $this->idx[] = array('serial');
        $this->idx[] = array('serial', 'name');

        // Table version. Increment when creating a db migration
        $this->schema_version = 1;

		// Create table if it does not exist
        $this->create_table();
        
        if($serial and $name)
        {
            $this->retrieve_one('serial=? AND name=?', array($serial, $name));
            $this->serial = $serial;
            $this->name = $name;
        }
        
        return $this;
    }
    
	// ------------------------------------------------------------------------

	/**
	 * Retrieve all entries for serial
	 *
	 * @param string serial
	 * @return array
	 * @author abn290
	 **/
    function all($serial)
    {
		$dbh=$this->getdbh();
        $out = array();
        foreach($this->retrieve_many( 'serial=?', $serial ) as $obj)
        {
            $out[$obj->name] = $obj->hash;
        }
        return $out;
        
    }

}
