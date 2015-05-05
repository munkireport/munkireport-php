<?php

class Machine_group extends Model {
    
    function __construct($groupid='', $property='')
    {
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['groupid'] = 0; $this->rt['groupid'] = 'VARCHAR(20)';
        $this->rs['property'] = '';
        $this->rs['value'] = '';

        $this->idx[] = array('property');
        $this->idx[] = array('value');

        // Table version. Increment when creating a db migration
        $this->schema_version = 0;

		// Create table if it does not exist
        $this->create_table();
        
        if($groupid and $property)
        {
            $this->retrieve_one('groupid=? AND property=?', array($groupid, $property));
            $this->groupid = $groupid;
            $this->property = $property;
        }
        
        return $this;
    }
    
	// ------------------------------------------------------------------------

	/**
	 * Retrieve all entries for groupid
	 *
	 * @param integer groupid
	 * @return array
	 * @author abn290
	 **/
    function all($groupid)
    {
		$dbh=$this->getdbh();
        $out = array('users' => array(), 'passhrases' => array());
        foreach($this->retrieve_many( 'groupid=?', $groupid ) as $obj)
        {
            switch($obj->property)
            {
                case 'user':
                    $out['users'][] = $obj->value;
                    break;
                case 'passphrase':
                    $out['passphrases'][] = $obj->value;
                    break;
                default:
                    $out[$obj->property] = $obj->value;
            }
        }
        return $out;
        
    }

}
