<?php

class Migration_certificate_add_issuer extends Model
{
    protected $columname = 'issuer';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'certificate';
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
                $sql = "CREATE TABLE certificate (
								id INTEGER PRIMARY KEY, 
								serial_number VARCHAR(255) UNIQUE, 
								cert_exp_time INTEGER,
								cert_path VARCHAR(255),
								cert_cn VARCHAR(255),
								timestamp INTEGER,
								issuer VARCHAR(255))";
                $this->exec($sql);

                $sql = "INSERT INTO machine_temp 
							SELECT id, serial_number, cert_exp_time, cert_path, cert_cn, timestamp, issuer FROM certificate";
                $this->exec($sql);

                $sql = "DROP table certificate";
                $this->exec($sql);

                $sql = "ALTER TABLE machine_temp RENAME TO certificate";
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
