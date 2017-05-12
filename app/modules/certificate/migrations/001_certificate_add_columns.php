<?php

class Migration_certificate_add_columns extends Model
{
    protected $new_columns = array(
        'issuer' => 'VARCHAR(255)',
        'cert_location' => 'VARCHAR(255)',
    );

    private $new_indexes = array(
        'cert_path',
        'cert_cn',
        'issuer',
        'cert_location',
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

        // Adding a column is simple...
        foreach ($this->new_columns as $column => $type) {
            $sql = "ALTER TABLE certificate ADD COLUMN $column $type";
            $this->exec($sql);
        }

        // Add new indexes
        foreach ($this->new_indexes as $index) {
            $sql = "CREATE INDEX certificate_$index ON `certificate` ($index)";
            $this->exec($sql);
        }

        $dbh->commit();
    }

    public function down()
    {
        // Don't bother about down as migrations are tied to git releases
    }
}
