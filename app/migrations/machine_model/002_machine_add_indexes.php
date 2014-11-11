<?php

// Add indexes to searchable fields in machine

class Migration_machine_add_indexes extends Model 
{
	
	private $fields = array('hostname', 'machine_model', 'machine_desc', 
			'current_processor_speed', 'cpu_arch', 'os_version', 
			'physical_memory', 'platform_UUID', 'number_processors', 
			'SMC_version_system', 'boot_rom_version', 'bus_speed', 
			'computer_name', 'l2_cache', 'machine_name', 'packages');
	
	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Wrap in transaction
		$dbh->beginTransaction();

		foreach($this->fields as $field)
		{
			$sql = "CREATE INDEX machine_$field ON machine ($field)";
			$this->exec($sql);
		}


		// Commit changes
		$dbh->commit();


	}// End function up()

	public function down()
	{
		// Get database handle
		$dbh = $this->getdbh();

		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		switch ($this->get_driver())
		{
			case 'sqlite':

				$sql = 'DROP INDEX machine_%s';

				break;

			case 'mysql':

				$sql = 'DROP INDEX machine_%s ON munkireport';

				break;

			default:

				$sql = 'UNKNOWN DRIVER';
				
		}

		// Wrap in transaction
		$dbh->beginTransaction();

		foreach($this->fields as $field)
		{
			$sql = sprintf($sql, $field);
			$this->exec($sql);
		}

		// Commit changes
		$dbh->commit();
	}
}
