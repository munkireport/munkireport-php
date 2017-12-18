<?php

use CFPropertyList\CFPropertyList;

class Ard_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'ard'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
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
       //$this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }

    // Override create_table to use illuminate/database capsule
    public function create_table() {
        // Check if we instantiated this table before
        if (isset($GLOBALS['tables'][$this->tablename])) {
            return true;
        }

        $capsule = $this->getCapsule();

        try {
            $exist = $capsule::table('ard')->limit(1)->count();
        } catch (PDOException $e) {
            $capsule::schema()->create('ard', function ($table) {
                $table->increments('id');
                $table->string('serial_number')->unique();
                $table->string('Text1');
                $table->string('Text2');
                $table->string('Text3');
                $table->string('Text4');

                $table->index('Text1', 'ard_Text1');
                $table->index('Text2', 'ard_Text2');
                $table->index('Text3', 'ard_Text3');
                $table->index('Text4', 'ard_Text4');
            });

//            // Store schema version in migration table
//            $migration = new Migration($this->tablename);
//            $migration->version = $this->schema_version;
//            $migration->save();
//
//            alert("Created table '$this->tablename' version $this->schema_version");
        }

        // Store this table in the instantiated tables array
        $GLOBALS['tables'][$this->tablename] = $this->tablename;

        // Create table succeeded
        return true;
    }

    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        
        $plist = $parser->toArray();

        foreach (array('Text1', 'Text2', 'Text3', 'Text4') as $item) {
            if (isset($plist[$item])) {
                $this->$item = $plist[$item];
            } else {
                $this->$item = '';
            }
        }

        $this->save();
    }
}
