<?php

class Migration_certificate_add_columns extends Model
{
    protected $addcols = array(
                'issuer' => VARCHAR(255),
                'cert_location'=> VARCHAR(255),
                );

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

        try {
            foreach ($this->addcols as $colname => $type) {
                $sql = sprintf(
                    'ALTER TABLE %s ADD COLUMN %s %s',
                    $this->tablename,
                    $colname,
                    $type
                );
                $this->exec($sql);
            }
        } catch (Exception $e) {
            // We do nothing here
        }

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
								timestamp INTEGER)";
                $this->exec($sql);

                $sql = "INSERT INTO certificate_temp 
							SELECT id, serial_number, cert_exp_time, cert_path, cert_cn, timestamp FROM certificate";
                $this->exec($sql);

                $sql = "DROP table certificate";
                $this->exec($sql);

                $sql = "ALTER TABLE certificate_temp RENAME TO certificate";
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
