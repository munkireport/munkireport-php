<?php

class Migration_munkireport_bigger_blob extends \Model
{
    public function up()
    {
        if ($this->get_driver() == 'mysql') {
            $sql = 'ALTER TABLE `munkireport` CHANGE `report_plist` `report_plist` MEDIUMBLOB  NULL';
            $this->exec($sql);
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
        if ($this->get_driver() == 'mysql') {
            $sql = 'ALTER TABLE `munkireport` CHANGE `report_plist` `report_plist` BLOB  NULL';
            $this->exec($sql);
        }
    }
}
