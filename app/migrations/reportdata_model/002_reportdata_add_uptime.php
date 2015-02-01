<?php

// Add uptime field

class Migration_reportdata_add_uptime extends Model 
{
	protected $columname = 'uptime';

	function __construct()
	{
		parent::__construct();
		$this->tablename = 'reportdata';
	}

	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		// Wrap in transaction
		$dbh->beginTransaction();

		// Adding a column is simple...
		$sql = sprintf('ALTER TABLE %s ADD COLUMN %s INTEGER DEFAULT 0', 
			$this->enquote($this->tablename), $this->enquote($this->columname));

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
				$sql = "CREATE TABLE %s_temp (
							id INTEGER PRIMARY KEY, 
							serial_number VARCHAR(255) UNIQUE, 
							console_user VARCHAR(255),
							long_username VARCHAR(255),
							remote_ip VARCHAR(255),
							reg_timestamp INTEGER,
							timestamp INTEGER)";
				$this->exec(sprintf($sql, $this->tablename));

				$sql = "INSERT INTO %s_temp 
							SELECT id, serial_number, console_user, long_username, remote_ip, reg_timestamp, timestamp
							FROM %s";
				$this->exec(sprintf($sql, $this->tablename, $this->tablename));

				$sql = "DROP table %s";
				$this->exec(sprintf($sql, $this->tablename));

				$sql = "ALTER TABLE %s_temp RENAME TO %s";
				$this->exec(sprintf($sql, $this->tablename, $this->tablename));

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
