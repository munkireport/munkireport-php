<?php

class Migration_timemachine_add_apfs_snapshots extends \Model
{
    protected $new_columns = array(
        'apfs_snapshots' => 'TEXT',
    );

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'timemachine';
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();

        // Adding a column is simple...
        foreach ($this->new_columns as $column => $type) {
            $sql = "ALTER TABLE timemachine ADD COLUMN $column $type";
            $this->exec($sql);
        }

        $dbh->commit();
    }

    public function down()
    {
        // Can't go back :/
    }
}
