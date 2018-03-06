<?php

namespace munkireport\models;

class Hash extends \Model
{

    public function __construct($serial = '', $name = '')
    {
        parent::__construct('id', 'hash'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = '';
        $this->rs['name'] = '';
        $this->rs['hash'] = '';
        $this->rs['timestamp'] = time();

        $this->idx[] = array('serial_number');
        $this->idx[] = array('serial_number', 'name');

        // Table version. Increment when creating a db migration
        $this->schema_version = 1;

        // Create table if it does not exist
        //$this->create_table();

        if ($serial and $name) {
            $this->retrieveOne('serial_number=? AND name=?', array($serial, $name));
            $this->serial_number = $serial;
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
    public function all($serial)
    {
        $dbh=$this->getdbh();
        $out = array();
        foreach ($this->retrieveMany('serial_number=?', $serial) as $obj) {
            $out[$obj->name] = $obj->hash;
        }
        return $out;
    }
}
