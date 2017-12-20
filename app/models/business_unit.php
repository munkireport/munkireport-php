<?php

namespace munkireport\models;

use \PDO;

class Business_unit extends \Model
{
    
    public function __construct($unitid = '', $property = '')
    {
        parent::__construct('id', 'business_unit'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['unitid'] = 0;
        $this->rs['property'] = '';
        $this->rs['value'] = '';

        $this->idx[] = array('property');
        $this->idx[] = array('value');

        // Table version. Increment when creating a db migration
        $this->schema_version = 0;

        // Create table if it does not exist
       //$this->create_table();
        
        if ($unitid and $property) {
            $this->retrieveOne('unitid=? AND property=?', array($unitid, $property));
            $this->unitid = $unitid;
            $this->property = $property;
        }
        
        return $this;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Retrieve all entries for unitid or all entries if unitid = empty
     *
     * @param integer unitid
     * @return array
     * @author abn290
     **/
    public function all($unitid = '')
    {
        $out = array();
        $where = $unitid ? 'unitid=?' : '';
        foreach ($this->select('unitid, property, value', $where, $unitid, PDO::FETCH_OBJ) as $obj) {
        // Initialize
            if (! isset($out[$obj->unitid])) {
                $out[$obj->unitid] = array('users' => array(), 'managers' => array(), 'machine_groups' => array());
            }

            switch ($obj->property) {
                case 'user':
                    $out[$obj->unitid]['users'][] = $obj->value;
                    break;
                case 'manager':
                    $out[$obj->unitid]['managers'][] = $obj->value;
                    break;
                case 'machine_group':
                    $out[$obj->unitid]['machine_groups'][] = intval($obj->value);
                    break;
                default:
                    $out[$obj->unitid][$obj->property] = $obj->value;
            }

            $out[$obj->unitid]['unitid'] = $obj->unitid;
        }

        if ($unitid && $out) {
            return $out[$unitid];
        } else {
            return array_values($out);
        }
    }

    /**
     * Get max unitid
     *
     * @return integer max unitid
     * @author AvB
     **/
    public function get_max_unitid()
    {
        $sql = 'SELECT MAX(unitid) AS max FROM '.$this->enquote($this->tablename);
        $result = $this->query($sql);
        return intval($result[0]->max);
    }

    /**
     * Get machinegroups for id
     *
     * @return array machine group ids
     * @author
     **/
    public function get_machine_groups($id)
    {
        $out = array();
        foreach ($this->retrieveMany('unitid=? AND property=?', array($id, 'machine_group')) as $obj) {
            $out[] = intval($obj->value);
        }
        return $out;
    }
}
