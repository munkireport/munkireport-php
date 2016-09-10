<?php

class Migration_machine_add_cpu extends Model
{
    protected $columname = 'cpu';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'machine';
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();

        // Adding a column is simple...
        $sql = sprintf(
            'ALTER TABLE %s ADD COLUMN %s VARCHAR(255)',
            $this->enquote($this->tablename),
            $this->enquote($this->columname)
        );

        $this->exec($sql);

        // so is adding an index...
        $idx_name = $this->tablename . '_' . $this->columname;
        $sql = sprintf(
            "CREATE INDEX %s ON %s (%s)",
            $idx_name,
            $this->enquote($this->tablename),
            $this->columname
        );

        $this->exec($sql);

        $dbh->commit();
    }

    public function down()
    {
        // Get database handle
        $dbh = $this->getdbh();

        switch ($this->get_driver()) {
            case 'sqlite':
                $dbh->beginTransaction();

                // Create temporary table
                $sql = "CREATE TABLE machine_temp (
								id INTEGER PRIMARY KEY, 
								serial_number VARCHAR(255) UNIQUE, 
								hostname VARCHAR(255),
								machine_model VARCHAR(255),
								machine_desc VARCHAR(255),
								img_url VARCHAR(255),
								current_processor_speed VARCHAR(255),
								cpu_arch VARCHAR(255),
								os_version VARCHAR(255),
								oci_server_version VARCHAR(255),
								physical_memory INTEGER,
								platform_UUID VARCHAR(255),
								number_processors INTEGER,
								SMC_version_system VARCHAR(255),
								boot_rom_version VARCHAR(255),
								bus_speed VARCHAR(255),
								computer_name VARCHAR(255),
								l2_cache VARCHAR(255),
								machine_name VARCHAR(255),
								packages VARCHAR(255))";
                $this->exec($sql);

                $sql = "INSERT INTO machine_temp 
							SELECT id, serial_number, hostname, machine_model, machine_desc, img_url, current_processor_speed, cpu_arch, os_version, physical_memory, platform_UUID, number_processors, SMC_version_system, boot_rom_version, bus_speed, computer_name, l2_cache, machine_name, packages FROM machine";
                $this->exec($sql);

                $sql = "DROP table machine";
                $this->exec($sql);

                $sql = "ALTER TABLE machine_temp RENAME TO machine";
                $this->exec($sql);

                $dbh->commit();

                break;

            default:
                // MySQL drops the index as well -> check for other engines
                $sql = sprintf(
                    'ALTER TABLE %s DROP COLUMN %s',
                    $this->enquote($this->tablename),
                    $this->enquote($this->columname)
                );
                $this->exec($sql);
        }
    }
}
