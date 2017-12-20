<?php

class Migration_homebrew_text_fields_increase extends \Model
{
    
      private $columns = array(
        'name',
        'full_name',
        'oldname',
        'aliases',
        'homepage',
        'installed_versions',
        'versions_stable',
        'linked_keg',
        'requirements',
        'conflicts_with'
    );
    
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
                    // SQLite uses TEXT for varchar, so no changes necessary
                    break;

                case 'mysql':
                    foreach ($this->columns as $column) {
                                                
                        // Drop indexes, they aren't supported with TEXT fields 
                        $sql = "ALTER TABLE homebrew DROP INDEX homebrew_$column";
                        $dbh->exec($sql);
                        
                        // Change column type
                        $sql = "ALTER TABLE homebrew CHANGE $column $column TEXT";
                        $dbh->exec($sql);
                    }
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
                break;

            case 'mysql':
                
                foreach ($this->columns as $column) {
                    $sql = "ALTER TABLE homebrew CHANGE $column $column VARCHAR(255)";
                    $dbh->exec($sql);
                    
                    // Create indes
                    $sql = "CREATE INDEX homeberw_$column ON homebrew $column";
                    $this->exec($sql);
                }
                break;

            default:
                throw new Exception("UNKNOWN DRIVER", 1);
        }
    }
}
