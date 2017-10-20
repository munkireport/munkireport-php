<?php

class Migration_add_filevault_users extends \Model
{
    protected $columname = 'filevault_users';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'filevault_status';
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
            $this->enquote($this->columname)
        );

        $this->exec($sql);

        // so is adding an index...
        $idx_name = $this->tablename . '_' . $this->columname;
        $sql = sprintf(
            "CREATE INDEX %s ON %s (%s)",
            $idx_name,
            $this->enquote($this->tablename),
            $this->columname
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
                $sql = "CREATE TABLE filevault_status_temp (
								id INTEGER PRIMARY KEY, 
								serial_number VARCHAR(255) UNIQUE, 
								filevault_status VARCHAR(255))";
                $this->exec($sql);

                $sql = "INSERT INTO filevault_status_temp 
							SELECT id, serial_number, filevault_status FROM filevault_status";
                $this->exec($sql);

                $sql = "DROP table filevault_status";
                $this->exec($sql);

                $sql = "ALTER TABLE filevault_status_temp RENAME TO filevault_status";
                $this->exec($sql);

                // Add index for old filevault_status column
                $idx_name = $this->tablename . '_filevault_status';
                $sql = sprintf(
                    "CREATE INDEX %s ON %s (%s)",
                    $idx_name,
                    $this->enquote($this->tablename),
                    'filevault_status'
                );

                $this->exec($sql);

                $dbh->commit();

                break;

            default:
                // MySQL drops the index as well -> check for other engines
                $sql = sprintf(
                    'ALTER TABLE %s DROP COLUMN %s',
                    $this->enquote($this->tablename),
                    $this->enquote($this->columname)
                );
                $this->exec($sql);
        }
    }
}
