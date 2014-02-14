<?php

// Add UNIQUE index to serial_number column

class Migration_munkireport_add_unique_index extends Model 
{
	
	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Wrap in transaction
		$dbh->beginTransaction();

		$sql = 'CREATE UNIQUE INDEX serial_number ON munkireport (serial_number)';
		$this->exec($sql);

		// Commit changes
		$dbh->commit();


	}// End function up()

	public function down()
	{
		// Get database handle
		$dbh = $this->getdbh();

		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Wrap in transaction
		$dbh->beginTransaction();

		$sql = 'DROP INDEX munkireport.serial_number';
		$this->exec($sql);

		// Commit changes
		$dbh->commit();
	}
}
