<?php

class Migration_machine_add_group extends Model
{
	protected $columname = 'computer_group';

	function __construct()
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

		// Wrap in transaction
		$dbh->beginTransaction();

		// Adding a column is simple...
		$sql = sprintf('ALTER TABLE %s ADD COLUMN %s INT DEFAULT 0', 
			$this->enquote($this->tablename), $this->enquote($this->columname));

		$this->exec($sql);

		// so is adding an index...
		$idx_name = $this->tablename . '_' . $this->columname;
		$sql = sprintf("CREATE INDEX %s ON %s (%s)", 
			$idx_name, $this->enquote($this->tablename), $this->columname);

		$this->exec($sql);

		$dbh->commit();
	}

	public function down()
	{
		// Get database handle
		$dbh = $this->getdbh();

		switch ($this->get_driver())
		{
			case 'sqlite':// ...removing a column in SQLite is hard

				$dbh->beginTransaction();

				// Create temporary table
				$sql = "CREATE TABLE machine_temp (
								id INTEGER PRIMARY KEY, 
								serial_number VARCHAR(255) UNIQUE, 
								hostname VARCHAR(255),
								machine_model VARCHAR(255),
								machine_desc VARCHAR(255),
								img_url VARCHAR(255),
								cpu VARCHAR(255),
								current_processor_speed VARCHAR(255),
								cpu_arch VARCHAR(255),
								os_version VARCHAR(255),
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
							SELECT id, serial_number, hostname, machine_model,
									machine_desc, img_url, cpu, current_processor_speed,
									cpu_arch, os_version, physical_memory, platform_UUID,
									number_processors, SMC_version_system, boot_rom_version,
									bus_speed, computer_name, l2_cache, machine_name, packages 
							FROM machine";
				$this->exec($sql);

				$sql = "DROP table machine";
				$this->exec($sql);

				$sql = "ALTER TABLE machine_temp RENAME TO machine";
				$this->exec($sql);

				// Call set indexes()
				$this->set_indexes();

				$dbh->commit();

				break;

			default: // MySQL (other engines?)

				// MySQL drops the index as well -> check for other engines
				$sql = sprintf('ALTER TABLE %s DROP COLUMN %s', 
				$this->enquote($this->tablename), $this->enquote($this->columname));
				$this->exec($sql);
		}
	}
}