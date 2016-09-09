<?php

class Migration_bluetooth_all_devices_in_seperate_db_row extends Model
{

    public function up()
    {
        // We drop the bluetooth table as these values change so
        // frequently that if the client doesn't checkin the values
        // are already out of date
        // Plus this is a major table change.
        
        // Get database handle
        $dbh = $this->getdbh();

        $sql = "DROP table bluetooth";
        $dbh->exec($sql);
    }// End function up()

    public function down()
    {
        // We can't go back to the previous version
        // But that is ok.
    }
}
