<?php

class Migration_homebrew_add_columns extends \Model
{
    protected $new_columns = array(
        'homebrew_git_config_file' => 'VARCHAR(255)',
        'homebrew_noanalytics_this_run' => 'VARCHAR(255)',
        'curl' => 'VARCHAR(255)',
    );

    private $new_indexes = array(
        'homebrew_git_config_file',
        'homebrew_noanalytics_this_run',
        'curl',
    );

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'homebrew_info';
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();

        // Adding a column is simple...
        foreach ($this->new_columns as $column => $type) {
            $sql = "ALTER TABLE homebrew_info ADD COLUMN $column $type";
            $this->exec($sql);
        }

        // Add new indexes
        foreach ($this->new_indexes as $index) {
            $sql = "CREATE INDEX homebrew_info_$index ON homebrew_info ($index)";
            $this->exec($sql);
        }

        $dbh->commit();
    }

    public function down()
    {
        // You can't get the beer back once you've drank it all away
    }
}
