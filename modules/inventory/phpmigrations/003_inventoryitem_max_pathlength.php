<?php

// Adjust the path column to allow for paths up to 1024 chars

class Migration_inventoryitem_max_pathlength extends \Model
{
    /**
     * Constructor
     *
     * Set up tablename and indexes
     *
     **/
    public function __construct()
    {
        parent::__construct('id', 'inventoryitem'); //primary key, tablename
    }

    /**
     * Migrate up
     *
     * Migrates this table to the current version
     *
     **/
    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                // SQLite uses TEXT for varchar, so no changes necessary
                break;

            case 'mysql':
                $sql = 'ALTER TABLE `inventoryitem` CHANGE `path` `path` VARCHAR(1024)';
                $dbh->exec($sql);
                break;

            default:
                throw new Exception("UNKNOWN DRIVER", 1);
        }
    }// End function up()

    /**
     * Migrate down
     *
     * Migrates this table to the previous version
     *
     **/
    public function down()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                break;

            case 'mysql':
                $sql = 'ALTER TABLE `inventoryitem` CHANGE `path` `path` VARCHAR(255)';
                $dbh->exec($sql);
                break;

            default:
                throw new Exception("UNKNOWN DRIVER", 1);
        }
    }
}
