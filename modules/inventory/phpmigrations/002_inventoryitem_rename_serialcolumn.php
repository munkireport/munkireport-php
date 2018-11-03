<?php

// Rename serial column to serial_number

class Migration_inventoryitem_rename_serialcolumn extends \Model
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
                try {
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Wrap in transaction
                    $dbh->beginTransaction();
                    
                    // Create temporary table
                    $sql = "CREATE TABLE inventoryitem_temp (id INTEGER PRIMARY KEY AUTOINCREMENT, serial_number VARCHAR(255), name VARCHAR(255), version VARCHAR(255), bundleid VARCHAR(255), bundlename VARCHAR(255), path VARCHAR(255))";
                    $dbh->exec($sql);
                    
                    // Copy data to temp table
                    $sql = "INSERT INTO inventoryitem_temp(serial_number, name, version, bundleid, bundlename, path) SELECT serial, name, version, bundleid, bundlename, path FROM inventoryitem";
                    $dbh->exec($sql);
                    
                    $sql = "DROP table inventoryitem";
                    $dbh->exec($sql);
                    
                    // Rename temp table
                    $sql = "ALTER TABLE inventoryitem_temp RENAME TO inventoryitem";
                    $dbh->exec($sql);
                    
                    // Set indexes
                    $sql = 'CREATE INDEX %s ON %s (%s)';
                    $this->idx['serial'] = array('serial_number');
                    $this->idx[] = array('name', 'version');
                    $this->set_indexes($sql);
                    
                    $dbh->commit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $this->errors .= "Failed: " . $e->getMessage();
                    return false;
                }
                break;

            case 'mysql':
                $sql = 'ALTER TABLE inventoryitem CHANGE serial serial_number VARCHAR(255)';
                $dbh->exec($sql);
                
                // No need to rebuild indexes, they stay intact.
                
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
                try {
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Wrap in transaction
                    $dbh->beginTransaction();
                    
                    // Create temporary table
                    $sql = "CREATE TABLE inventoryitem_temp (id INTEGER PRIMARY KEY AUTOINCREMENT, serial VARCHAR(255), name VARCHAR(255), version VARCHAR(255), bundleid VARCHAR(255), bundlename VARCHAR(255), path VARCHAR(255))";
                    $dbh->exec($sql);
                    
                    // Copy data to temp table
                    $sql = "INSERT INTO inventoryitem_temp(serial, name, version, bundleid, bundlename, path) SELECT serial_number, name, version, bundleid, bundlename, path FROM inventoryitem";
                    $dbh->exec($sql);
                    
                    $sql = "DROP table inventoryitem";
                    $dbh->exec($sql);
                    
                    // Rename temp table
                    $sql = "ALTER TABLE inventoryitem_temp RENAME TO inventoryitem";
                    $dbh->exec($sql);
                    
                    // Set indexes
                    $sql = 'CREATE INDEX %s ON %s (%s)';
                    $this->idx[] = array('serial');
                    $this->idx[] = array('name', 'version');
                    $this->set_indexes($sql);

                    
                    $dbh->commit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $this->errors .= "Failed: " . $e->getMessage();
                    return false;
                }
                break;

            case 'mysql':
                $sql = 'ALTER TABLE inventoryitem CHANGE serial_number serial VARCHAR(255)';
                $dbh->exec($sql);
                
                // No need to rebuild indexes, they stay intact.
                
                break;

            default:
                throw new Exception("UNKNOWN DRIVER", 1);
        }
    }
}
