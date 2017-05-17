<?php

class Migration_gsx_fix_type extends Model
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $ctl = new Controller;
        if( ! $ctl->authorized('global'))
        {
            throw new Exception("Only migrate in admin session", 1);
        }

        parent::__construct('id', 'gsx'); //primary key, tablename
    }


    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // ***** Modify columns

        if ($this->get_driver() == 'mysql')
        {
            // Get columns and data types
            $sql = "ALTER TABLE gsx MODIFY daysremaining INT(11)";
            $this->exec($sql);

            // Wrap in transaction
            $dbh->beginTransaction();

            // Add indexes
            $this->idx[] = array('warrantystatus');
            $this->idx[] = array('coverageenddate');
            $this->idx[] = array('estimatedpurchasedate');
            $this->idx[] = array('daysremaining');
            $this->idx[] = array('isvintage');
            $this->idx[] = array('configdescription');
            $sql = 'CREATE INDEX %s ON %s (%s)';
            $this->set_indexes($sql);

            // Commit transaction
            $dbh->commit();

        }
        else{

            // Wrap in transaction
            $dbh->beginTransaction();

            // Create a temporary table
            $sql = "CREATE TABLE gsx_temp (
                        id INTEGER PRIMARY KEY,
                        serial_number VARCHAR(255) UNIQUE,
                        warrantystatus VARCHAR(255),
                        coverageenddate VARCHAR(255),
                        coveragestartdate VARCHAR(255),
                        daysremaining int(11),
                        estimatedpurchasedate VARCHAR(255),
                        purchasecountry VARCHAR(255),
                        registrationdate VARCHAR(255),
                        productdescription VARCHAR(255),
                        configdescription VARCHAR(255),
                        contractcoverageenddate VARCHAR(255),
                        contractcoveragestartdate VARCHAR(255),
                        contracttype VARCHAR(255),
                        laborcovered VARCHAR(255),
                        partcovered VARCHAR(255),
                        warrantyreferenceno VARCHAR(255),
                        isloaner VARCHAR(255),
                        warrantymod VARCHAR(255),
                        isvintage VARCHAR(255),
                        isobsolete VARCHAR(255))";
            $this->exec($sql);

            // Copy everything to the temp table
            $sql = "INSERT INTO gsx_temp
                        SELECT id, serial_number, warrantystatus, coverageenddate, coveragestartdate, daysremaining, estimatedpurchasedate, purchasecountry, registrationdate, productdescription, configdescription, contractcoverageenddate, contractcoveragestartdate, contracttype, laborcovered, partcovered, warrantyreferenceno, isloaner, warrantymod, isvintage, isobsolete FROM gsx";
            $this->exec($sql);

            // Drop original table
            $sql = "DROP table gsx";
            $this->exec($sql);

            // Rename temp table
            $sql = "ALTER TABLE gsx_temp RENAME TO gsx";
            $this->exec($sql);

            // Add indexes
            $this->idx[] = array('warrantystatus');
            $this->idx[] = array('coverageenddate');
            $this->idx[] = array('estimatedpurchasedate');
            $this->idx[] = array('daysremaining');
            $this->idx[] = array('isvintage');
            $this->idx[] = array('configdescription');
            $sql = 'CREATE INDEX IF NOT EXISTS %s ON %s (%s)';
            $this->set_indexes($sql);

            // Commit transaction
            $dbh->commit();
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
        // No down
    }
}
