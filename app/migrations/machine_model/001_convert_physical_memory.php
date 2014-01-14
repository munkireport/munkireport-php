<?php

class Migration_convert_physical_memory extends Model 
{
	
	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		switch ($this->get_driver())
		{
			case 'sqlite':

				try 
				{  
		  			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					// Wrap in transaction
					$dbh->beginTransaction();

					// Remove 'GB' from physical memory entries
					$sql = "UPDATE machine SET physical_memory = CAST(physical_memory AS INTEGER)";
					$dbh->exec($sql);

					// Set physical_memory column to INTEGER affinity
					// Unfortunately this is not very simple
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
					$dbh->exec($sql);

					$sql = "INSERT INTO machine_temp SELECT * FROM machine";
					$dbh->exec($sql);

					$sql = "DROP table machine";
					$dbh->exec($sql);

					$sql = "ALTER TABLE machine_temp RENAME TO machine";
					$dbh->exec($sql);

					$dbh->commit();
				}
				catch (Exception $e) 
				{
					$dbh->rollBack();
					$this->errors .= "Failed: " . $e->getMessage();
					return FALSE;
				}
				break;

			case 'mysql':

				// Remove 'GB' from physical memory entries
				$sql = "UPDATE machine SET physical_memory = SUBSTRING_INDEX(physical_memory, ' ', 1)";
				$dbh->exec($sql);

				// Set physical_memory column to INT
				$sql = "ALTER TABLE machine MODIFY physical_memory INT";
				$dbh->exec($sql);

				break;

			default:

				# code...
				break;
		}
	}// End function up()

	public function down()
	{
		// Get database handle
		$dbh = $this->getdbh();

		switch ($this->get_driver())
		{
			case 'sqlite':
				try 
				{  
					$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					// Wrap in transaction
					$dbh->beginTransaction();

					// Convert physical_memory column to VARCHAR
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
								physical_memory VARCHAR(255), 
								platform_UUID VARCHAR(255), 
								number_processors INTEGER, 
								SMC_version_system VARCHAR(255), 
								boot_rom_version VARCHAR(255), 
								bus_speed VARCHAR(255), 
								computer_name VARCHAR(255), 
								l2_cache VARCHAR(255), 
								machine_name VARCHAR(255), 
								packages VARCHAR(255))";
					$this->query($sql);

					$sql = "INSERT INTO machine_temp SELECT * FROM machine";
					$this->query($sql);

					$sql = "DROP table machine";
					$this->query($sql);
					
					$sql = "ALTER TABLE machine_temp RENAME TO machine";
					$this->query($sql);

					// Add GB to physical memory entries
					$sql = "UPDATE machine SET physical_memory = physical_memory || ' GB'";
					$this->query($sql);

					$dbh->commit();
				}
				catch (Exception $e) 
				{
					$dbh->rollBack();
					$this->errors .= "Failed: " . $e->getMessage();
					return FALSE;
				}
				break;

			case 'mysql':
				
				// Convert physical_memory column to VARCHAR
				$sql = "ALTER TABLE machine MODIFY physical_memory VARCHAR(255)";
				$this->query($sql);

				// Add GB to physical memory entries
				$sql = "UPDATE machine SET physical_memory = CONCAT(physical_memory, ' GB')";
				$this->query($sql);

				break;

			default:
				# code...
				break;
		}
	}
}
