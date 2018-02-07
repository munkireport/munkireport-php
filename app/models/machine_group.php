<?php

namespace munkireport\models;

use \PDO;

class Machine_group extends \Model
{
    
    public function __construct($groupid = '', $property = '')
    {
        parent::__construct('id', 'machine_group'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['groupid'] = 0;
        $this->rs['property'] = '';
        $this->rs['value'] = '';

        $this->idx[] = array('property');
        $this->idx[] = array('value');

        // Table version. Increment when creating a db migration
        $this->schema_version = 0;

        // Create table if it does not exist
        //$this->create_table();
        
        if ($groupid and $property) {
            $this->retrieveOne('groupid=? AND property=?', array($groupid, $property));
            $this->groupid = $groupid;
            $this->property = $property;
        }
        
        return $this;
    }

    /**
     * Get max groupid
     *
     * @return integer max groupid
     * @author AvB
     **/
    public function get_max_groupid()
    {
        $sql = 'SELECT MAX(groupid) AS max FROM '.$this->enquote($this->tablename);
        $result = $this->query($sql);
        return intval($result[0]->max);
    }

    /**
     * Select unique group ids
     *
     * @return void
     * @author
     **/
    public function get_group_ids()
    {
        $out = array();
        $sql = "SELECT groupid FROM $this->tablename GROUP BY groupid";
        foreach ($this->query($sql) as $obj) {
            $out[] = $obj->groupid;
        }

        return $out;
    }
    
    // ------------------------------------------------------------------------

    /**
     * Retrieve all entries for groupid
     *
     * @param integer groupid
     * @return array
     * @author abn290
     **/
    public function all($groupid = '')
    {
        $out = array();
        $where = $groupid !== '' ? 'groupid=?' : '';

        foreach ($this->select('groupid, property, value', $where, $groupid, PDO::FETCH_OBJ) as $obj) {
            switch ($obj->property) {
                case 'key':
                    $out[$obj->groupid]['keys'][] = $obj->value;
                    break;
                default:
                    $out[$obj->groupid][$obj->property] = $obj->value;
            }

            $out[$obj->groupid]['groupid'] = intval($obj->groupid);
        }

        if (! isset($obj)) {
            return array();
        }

        if ($groupid !== '' && $out) {
            return $out[$groupid];
        } else {
            return array_values($out);
        }
    }
}
