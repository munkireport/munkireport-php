<?php

// Add uptime field

class Migration_timemachine_add_kind_location_name extends Model 
{
	protected $columname1 = 'kind';
	protected $columname2 = 'location_name';
	protected $columnname3 = 'backup_location';

	function __construct()
	{
		parent::__construct();
		$this->tablename = 'timemachine';
	}

	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		// Wrap in transaction
		$dbh->beginTransaction();

		// Adding a column is simple...
		$sql = sprintf('ALTER TABLE %s ADD COLUMN %s VARCHAR(255)', 
			$this->enquote($this->tablename), $this->enquote($this->columname1));

		$this->exec($sql);

		// so is adding an index...
		$idx_name = $this->tablename . '_' . $this->columname1;
		$sql = sprintf("CREATE INDEX %s ON %s (%s)", 
			$idx_name, $this->enquote($this->tablename), $this->columname1);

		$this->exec($sql);
		
		// Adding a column is simple...
		$sql = sprintf('ALTER TABLE %s ADD COLUMN %s VARCHAR(255)', 
			$this->enquote($this->tablename), $this->enquote($this->columname2));

		$this->exec($sql);

		// so is adding an index...
		$idx_name = $this->tablename . '_' . $this->columname2;
		$sql = sprintf("CREATE INDEX %s ON %s (%s)", 
			$idx_name, $this->enquote($this->tablename), $this->columname2);

		// Adding a column is simple...
		$sql = sprintf('ALTER TABLE %s ADD COLUMN %s VARCHAR(255)', 
			$this->enquote($this->tablename), $this->enquote($this->columname3));

		$this->exec($sql);

		// so is adding an index...
		$idx_name = $this->tablename . '_' . $this->columname3;
		$sql = sprintf("CREATE INDEX %s ON %s (%s)", 
			$idx_name, $this->enquote($this->tablename), $this->columname3);

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
							last_success VARCHAR(255),
							last_failure VARCHAR(255),
							last_failure_msg VARCHAR(255),
							duration INTEGER,
							timestamp VARCHAR)";
				$this->exec(sprintf($sql, $this->tablename));

				$sql = "INSERT INTO %s_temp 
							SELECT id, serial_number, last_success, last_failure, last_failure_msg, duration, timestamp
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
