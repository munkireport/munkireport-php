<?php

// This migration converts fixes a type error introduced in 2.9.0
// The migration will check the database and fix it when necessary

class Migration_munkireport_fix_type extends \Model
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $ctl = new Controller;
        if( ! $ctl->authorized('global'))
        {
            throw new Exception("Only migrate in admin session", 1);
        }

        parent::__construct('id', 'munkireport'); //primary key, tablename
    }


    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();
        
        // ***** Modify columns

        if ($this->get_driver() == 'mysql')
        {
            // Get columns and data types
            $sql = "ALTER TABLE munkireport MODIFY errors INT(11)";
            $this->exec($sql);

            $sql = "ALTER TABLE munkireport MODIFY warnings INT(11)";
            $this->exec($sql);

            $sql = "ALTER TABLE munkireport MODIFY error_json BLOB";
            $this->exec($sql);

            $sql = "ALTER TABLE munkireport MODIFY warning_json BLOB";
            $this->exec($sql);

        }
        else{
            
            // Wrap in transaction
            $dbh->beginTransaction();
                        
            // Create a temporary table
            $sql = "CREATE TABLE munkireport_temp (
                        id INTEGER PRIMARY KEY,
                        serial_number VARCHAR(255) UNIQUE,
                        timestamp VARCHAR(255),
                        runtype VARCHAR(255),
                        starttime VARCHAR(255),
                        endtime varchar(255),
                        version varchar(255),
                        errors int(11),
                        warnings int(11),
                        manifestname varchar(255),
                        error_json BLOB,
                        warning_json BLOB)";
            $this->exec($sql);

            // Copy everything to the temp table
            $sql = "INSERT INTO munkireport_temp
                        SELECT id, serial_number, timestamp, runtype, starttime, endtime, version, errors, warnings, manifestname, error_json, warning_json FROM munkireport";
            $this->exec($sql);

            // Drop original table
            $sql = "DROP table munkireport";
            $this->exec($sql);

            // Rename temp table
            $sql = "ALTER TABLE munkireport_temp RENAME TO munkireport";
            $this->exec($sql);
            
            // Add indexes
            $this->idx[] = array('timestamp');
            $this->idx[] = array('runtype');
            $this->idx[] = array('version');
            $this->idx[] = array('errors');
            $this->idx[] = array('warnings');
            $this->idx[] = array('manifestname');
            $sql = 'CREATE INDEX IF NOT EXISTS %s ON %s (%s)';
            $this->set_indexes($sql);
            
            // Commit transaction
            $dbh->commit();

        }


        //throw new Exception("Error Processing Request", 1);



    }// End function up()

    /**
     * Migrate down
     *
     * Migrates this table to the previous version
     *
     **/
    public function down()
    {

        // No down
    }



}
