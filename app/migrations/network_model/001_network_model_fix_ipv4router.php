<?php

// Fix the ipv4 router: was integer, should be varchar

class Migration_network_model_fix_ipv4router extends Model 
{
	
	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Wrap in transaction
		$dbh->beginTransaction();

		switch ($this->get_driver())
		{
			case 'sqlite':

				// Set IPv4 router to string affinity
				// Unfortunately this is not very simple
				$sql = "CREATE TABLE network_temp (
							id INTEGER PRIMARY KEY, 
							serial_number VARCHAR(255) UNIQUE, 
							service VARCHAR(255), 
							`order` INTEGER, 
							status INTEGER, 
							ethernet VARCHAR(255), 
							clientid VARCHAR(255), 
							ipv4conf VARCHAR(255), 
							ipv4ip VARCHAR(255), 
							ipv4mask VARCHAR(255), 
							ipv4router VARCHAR(255), 
							ipv6conf VARCHAR(255), 
							ipv6ip VARCHAR(255), 
							ipv6prefixlen INTEGER, 
							ipv6router VARCHAR(255), 
							timestamp INTEGER)";
				$this->exec($sql);

				$sql = "INSERT INTO network_temp SELECT * FROM network";
				$this->exec($sql);

				$sql = "DROP table network";
				$this->exec($sql);

				$sql = "ALTER TABLE network_temp RENAME TO network";
				$this->exec($sql);

				// Re-add indexes
				$this->tablename = 'network';
				$this->idx[] = array('serial_number');
				$this->idx[] = array('serial_number', 'service');
				$this->set_indexes();
				
				break;

			case 'mysql':

				// Set ipv4router column to VARCHAR
				$sql = "ALTER TABLE network MODIFY ipv4router VARCHAR(255)";
				$this->exec($sql);

				break;

			default:

				# code...
				break;
		}

		// Commit changes
		$dbh->commit();


	}// End function up()

	public function down()
	{
		// As this is an error correction, there's no need for down(). 
		// up() is idempotent
	}
}
