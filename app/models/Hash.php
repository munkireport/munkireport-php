<?php

class Hash extends Model {
    
    function __construct($serial='', $name='')
    {
        parent::__construct('id','hash'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial'] = '';
        $this->rs['name'] = '';
        $this->rs['hash'] = '';
        $this->rs['timestamp'] = time();
				
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

	// ------------------------------------------------------------------------

	/**
	 * Count items with name
	 *
	 * @param string name
	 * @return int
	 * @author abn290
	 **/
	function count($name)
	{
		$dbh=$this->getdbh();
		$sql = 'SELECT count(*) as count FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( 'name' ).'=?';
        $stmt = $dbh->prepare( $sql );
        $stmt->bindValue( 1, $name );
        $stmt->execute();
        if($rs = $stmt->fetch( PDO::FETCH_OBJ ))
		{
			return $rs->count;
		}
		return 0;
	}

}
