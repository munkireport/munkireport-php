<?php

// Fix indexes for MySQL that were not created when migrating
// from 2.0.7 and before

class Migration_installhistory_fix_indexes extends Model
{
    /**
     * Constructor
     *
     * Set up tablename and indexes
     *
     **/
    public function __construct()
    {
        parent::__construct('id', 'installhistory'); //primary key, tablename

        // Set indexes
        $this->idx[] = array('serial_number');
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
                // In SQLite we just use 'IF NOT EXISTS'
                $sql = 'CREATE INDEX IF NOT EXISTS %s ON %s (%s)';

                break;

            case 'mysql':
                $sql = 'CREATE INDEX %s ON %s (%s)';

                // Look up existing indexes
                $indexes = $this->query("SELECT index_name 
						FROM INFORMATION_SCHEMA.STATISTICS
						WHERE table_schema = DATABASE() 
						AND table_name = '".$this->get_table_name()."'");

                foreach ($indexes as $obj) {
                    foreach ($this->idx as $k => $idx_data) {
                        if ($obj->index_name == $this->get_index_name($idx_data)) {
                            // If index exists, unset from index
                            unset($this->idx[$k]);
                        }
                    }
                }

                break;

            default:
                throw new Exception("UNKNOWN DRIVER", 1);
        }

        // Call set indexes()
        $this->set_indexes($sql);
    }// End function up()

    /**
     * Migrate down
     *
     * Migrates this table to the previous version
     *
     **/
    public function down()
    {
        // There is no down() as this is a bugfix and up() is idempotent
    }
}
