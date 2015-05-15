<?php

class Machine_group extends Model {
    
    function __construct($groupid='', $property='')
    {
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['groupid'] = 0;
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
    function all($groupid = '')
    {
        $out = array();
        $where = $groupid ? 'groupid=?' : '';
        foreach($this->select( 'groupid, property, value', $where, $groupid, PDO::FETCH_OBJ ) as $obj)
        {
            switch($obj->property)
            {
                case 'key':
                    $out[$obj->groupid]['keys'][] = $obj->value;
                    break;
                default:
                    $out[$obj->groupid][$obj->property] = $obj->value;
            }

            $out[$obj->groupid]['groupid'] = intval($obj->groupid);

            // Add filter value
            $out[$obj->groupid]['checked'] = TRUE;
            if(isset($_SESSION['filter']) && $_SESSION['filter'])
            {
                $out[$obj->groupid]['checked'] = ! in_array($obj->groupid, $_SESSION['filter']);
            }

        }

        if($groupid && $out)
        {
            return $out[$groupid];
        }
        else
        {
            return array_values($out);
        }     
        
    }

}
