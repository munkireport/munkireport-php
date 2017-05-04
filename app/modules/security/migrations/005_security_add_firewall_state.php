<?php

class Migration_security_add_ssh extends Model
{
	protected $columnname = 'firewall_state';

	public function __construct()
	{
		parent::__construct();
		$this->tablename = 'security';
	}


	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		$dbh->beginTransaction();
		$sql = sprintf(
			'ALTER TABLE %s ADD COLUMN %s VARCHAR(255) DEFAULT NULL',
			$this->enquote($this->tablename),
			$this->enquote($this->columnname)
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
			
			// Create temp table
			$sql = "CREATE TABLE security_temp (
					id INTEGER PRIMARY KEY,
					serial_number VARCHAR(255) UNIQUE,
					gatekeeper VARCHAR(255),
					sip VARCHAR(255),
					ssh_users VARCHAR(255),
					ard_users VARCHAR(255),
					firmwarepw VARCHAR(255)
				)";
			$this->exec($sql);
			
			// Copy data into temp table
			$sql = "INSERT INTO security_temp (
					SELECT id, serial_number, gatekeeper, sip, ssh_users, ard_users, firmwarepw
					FROM security";
			$this->exec($sql);

			// Drop old table and rename temp
			$sql = "DROP table security";
			$this->exec($sql);

			$sql = "ALTER TABLE security_temp RENAME TO security";
			$this->exec($sql);

			break;
		
		default:
			$sql = sprintf(
				'ALTER TABLE %s DROP COLUMN %s',
				$this->enquote($this->tablename),
				$this->enquote($this->columnname)
			);
			$this->exec($sql);
		}
	}
}
