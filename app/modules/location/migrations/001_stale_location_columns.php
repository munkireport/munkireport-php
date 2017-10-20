<?php

class Migration_stale_location_columns extends \Model
{
    protected $columname1 = 'stalelocation';
    protected $columname2 = 'lastlocationrun';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'location';
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();

        // Adding a column is simple...
        $sql = sprintf(
            'ALTER TABLE %s ADD COLUMN %s VARCHAR(255)',
            $this->enquote($this->tablename),
            $this->enquote($this->columname1)
        );

        $this->exec($sql);

        // so is adding an index...
        $idx_name = $this->tablename . '_' . $this->columname1;
        $sql = sprintf(
            "CREATE INDEX %s ON %s (%s)",
            $idx_name,
            $this->enquote($this->tablename),
            $this->columname1
        );

        $this->exec($sql);
        
        // Adding a column is simple...
        $sql = sprintf(
            'ALTER TABLE %s ADD COLUMN %s VARCHAR(255)',
            $this->enquote($this->tablename),
            $this->enquote($this->columname2)
        );

        $this->exec($sql);

        // so is adding an index...
        $idx_name = $this->tablename . '_' . $this->columname2;
        $sql = sprintf(
            "CREATE INDEX %s ON %s (%s)",
            $idx_name,
            $this->enquote($this->tablename),
            $this->columname2
        );

        $this->exec($sql);

        $dbh->commit();
    }

    public function down()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                $dbh->beginTransaction();

                // Create temporary table
                $sql = "CREATE TABLE %_temp (id INTEGER PRIMARY KEY, serial_number VARCHAR(255) UNIQUE, address VARCHAR(255), altitude INTEGER, currentstatus VARCHAR(255), ls_enabled INTEGER, lastrun VARCHAR(255), latitude REAL, latitudeaccuracy INTEGER, longitude REAL, longitudeaccuracy INTEGER)";
                $this->exec(sprintf($sql, $this->tablename));

                $sql = "INSERT INTO %_temp 
							SELECT id, serial_number, address, altitude, currentstatus, ls_enabled, lastrun, latitude, latitudeaccuracy, longitude, longitudeaccuracy
							FROM %s";
                $this->exec(sprintf($sql, $this->tablename, $this->tablename));

                $sql = "DROP table %s";
                $this->exec(sprintf($sql, $this->tablename));

                $sql = "ALTER TABLE %_temp RENAME TO %s";
                $this->exec(sprintf($sql, $this->tablename, $this->tablename));

                $dbh->commit();

                break;

            case 'mysql':
                // MySQL drops the index as well -> check for other engines
                $sql = sprintf(
                    'ALTER TABLE %s DROP COLUMN %s',
                    $this->enquote($this->tablename),
                    $this->enquote($this->columname1)
                );
                $this->exec($sql);
                
                $sql = sprintf(
                    'ALTER TABLE %s DROP COLUMN %s',
                    $this->enquote($this->tablename),
                    $this->enquote($this->columname2)
                );
                $this->exec($sql);
            
            default:
                # code here...
                break;
        }
    }
}
