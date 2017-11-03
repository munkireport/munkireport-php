<?php

class Migration_reportdata_add_group extends \Model
{
    protected $columname = 'machine_group';

    public function __construct()
    {
        parent::__construct('id', 'reportdata'); //primary key, tablename

        $this->idx[] = array('console_user');
        $this->idx[] = array('long_username');
        $this->idx[] = array('remote_ip');
        $this->idx[] = array('reg_timestamp');
        $this->idx[] = array('timestamp');
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();

        // Adding a column is simple...
        $sql = sprintf(
            'ALTER TABLE %s ADD COLUMN %s INT DEFAULT 0',
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
                $sql = "CREATE TABLE reportdata_temp (
								id INTEGER PRIMARY KEY, 
								serial_number VARCHAR(255) UNIQUE, 
								console_user VARCHAR(255),
								long_username VARCHAR(255),
								uptime INTEGER DEFAULT 0,
								reg_timestamp INTEGER,
								timestamp INTEGER)";
                $this->exec($sql);


                $sql = "INSERT INTO reportdata_temp 
							SELECT id, serial_number, console_user, long_username,
									uptime, reg_timestamp, timestamp 
							FROM reportdata";
                $this->exec($sql);

                $sql = "DROP table reportdata";
                $this->exec($sql);

                $sql = "ALTER TABLE reportdata_temp RENAME TO reportdata";
                $this->exec($sql);

                // Call set indexes()
                $this->set_indexes();

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
