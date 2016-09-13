<?php

/**
 * Convert osversion column to integer
 *
 **/
class Migration_machine_osversion_integer extends Model
{

    public function __construct()
    {
        parent::__construct('id', 'machine'); //primary key, tablename

        $this->idx['hostname'] = array('hostname');
        $this->idx['machine_model'] = array('machine_model');
        $this->idx['machine_desc'] = array('machine_desc');
        $this->idx['cpu'] = array('cpu');
        $this->idx['current_processor_speed'] = array('current_processor_speed');
        $this->idx['cpu_arch'] = array('cpu_arch');
        $this->idx['os_version'] = array('os_version');
        $this->idx['physical_memory'] = array('physical_memory');
        $this->idx['platform_UUID'] = array('platform_UUID');
        $this->idx['number_processors'] = array('number_processors');
        $this->idx['SMC_version_system'] = array('SMC_version_system');
        $this->idx['boot_rom_version'] = array('boot_rom_version');
        $this->idx['bus_speed'] = array('bus_speed');
        $this->idx['computer_name'] = array('computer_name');
        $this->idx['l2_cache'] = array('l2_cache');
        $this->idx['machine_name'] = array('machine_name');
        $this->idx['packages'] = array('packages');
    }
    
    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                try {
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Wrap in transaction
                    $dbh->beginTransaction();

                    // Get all os_versions
                    $versions = array();
                    $sql = "SELECT id, os_version FROM machine WHERE os_version LIKE '%.%'";
                    foreach ($dbh->query($sql) as $arr) {
                        $versions[$arr['id']] = $arr['os_version'];
                    }

                    foreach ($versions as $id => $value) {
                        $digits = explode('.', $value);
                        $mult = 10000;
                        $value = 0;
                        foreach ($digits as $digit) {
                            $value += $digit * $mult;
                            $mult = $mult / 100;
                        }
                        $sql = "UPDATE machine SET os_version = $value WHERE id = $id";
                        $dbh->exec($sql);
                    }

                    // Get create table syntax
                    $sql = "SELECT sql FROM sqlite_master WHERE type='table' AND name='machine'";
                    foreach ($dbh->query($sql) as $arr) {
                        $sql = preg_replace('/(`?os_version`?) VARCHAR\(255\)/', '$1 INT', $arr['sql'], 1, $changed);
                        if (! $changed) {
                            throw new Exception('Could not create temporary table as machine table is in an unknown state.');
                        }
                        $sql = preg_replace('/"?machine"?/', 'machine_temp', $sql, 1, $changed);
                        if (! $changed) {
                            throw new Exception('Could not create temporary table as machine table is in an unknown state.');
                        }
                    }

                    // Create temp table in correct format
                    $dbh->exec($sql);

                    // Copy data to temp table
                    $sql = "INSERT INTO machine_temp SELECT * FROM machine";
                    $dbh->exec($sql);

                    $sql = "DROP table machine";
                    $dbh->exec($sql);

                    $sql = "ALTER TABLE machine_temp RENAME TO machine";
                    $dbh->exec($sql);

                    // Call set indexes()
                    $this->set_indexes();

                    $dbh->commit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $this->errors .= "Failed: " . $e->getMessage();
                    return false;
                }
                break;

            case 'mysql':
                // Complex query to convert os_version to integer
                $sql = "UPDATE machine SET os_version =
				SUBSTRING_INDEX(os_version, '.', 1) * 10000 +
			   (IF(  length(os_version) - length(replace(os_version, '.', ''))>=1,  
			       SUBSTRING_INDEX(SUBSTRING_INDEX(os_version, '.', 2), '.', -1) ,0) * 100) +
			   (IF(  length(os_version) - length(replace(os_version, '.', ''))>=2,  
			       SUBSTRING_INDEX(SUBSTRING_INDEX(os_version, '.', 3), '.', -1) ,0))
				WHERE os_version LIKE '%.%'";

                $dbh->exec($sql);

                // Set os_version column to INT
                $sql = "ALTER TABLE machine MODIFY os_version INT";
                $dbh->exec($sql);

                break;

            default:
                # code...
                break;
        }
    }// End function up()

    public function down()
    {
        return false; // No Down function
    }
}
