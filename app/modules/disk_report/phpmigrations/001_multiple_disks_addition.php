<?php

class Migration_multiple_disks_addition extends \Model
{
    public $rt = array();

    public function __construct()
    {
        parent::__construct('id', 'diskreport_temp'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = '';
        $this->rs['TotalSize'] = 0;
        $this->rt['TotalSize'] = 'BIGINT';
        $this->rs['FreeSpace'] = 0;
        $this->rt['FreeSpace'] = 'BIGINT';
        $this->rs['Percentage'] = 0;
        $this->rs['SMARTStatus'] = '';
        $this->rs['VolumeType'] = '';
        $this->rs['BusProtocol'] = '';
        $this->rs['Internal'] = 0;
        $this->rs['MountPoint'] = '';
        $this->rs['VolumeName'] = '';
        $this->rs['CoreStorageEncrypted'] = 0;
        $this->rs['timestamp'] = 0;

        // Drop temp table
        $dbh = $this->getdbh();
        $dbh->query("DROP TABLE IF EXISTS diskreport_temp");
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Check if database is already migrated
        try {
            // This will cause an Exception if not migrated
            $dbh->query("SELECT VolumeName FROM diskreport");
            return true;
        } catch (Exception $e) {
        // Not migrated, continue..
        }

        // Create temp table
        create_table($this);

        // Copy data into temp
        $now = time();
        $sql = "INSERT INTO diskreport_temp 
					SELECT id, serial_number, TotalSize, FreeSpace, Percentage, SMARTStatus, CASE WHEN SolidState = 1 THEN 'ssd' ELSE 'hdd' END, 'unknown', 1, '/', 'Unknown', -1, $now FROM diskreport";
        
        $this->exec($sql);

        $sql = "DROP table diskreport";
        $this->exec($sql);

        $sql = "ALTER TABLE diskreport_temp RENAME TO diskreport";
        $this->exec($sql);

        // Build indexes
        $this->tablename = 'diskreport';
        $this->idx[] = array('serial_number');
        $this->idx[] = array('VolumeType');
        $this->idx[] = array('MountPoint');
        $this->idx[] = array('VolumeName');
        $this->set_indexes();
    }// End function up()

    public function down()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // No down yet
    }
}
