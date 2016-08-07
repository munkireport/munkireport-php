<?php

// Add columns for timemachine

class Migration_timemachine_add_kind_location_name extends Model 
{
	protected $columns = array(
				'kind' => 'VARCHAR(255)',
				'location_name' => 'VARCHAR(255)',
				'backup_location' => 'VARCHAR(255)',
				'destinations' => 'INT');

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
		
		// Loop over columns
		foreach($this->columns as $name => $type){
			// Adding a column is simple...
			$sql = sprintf('ALTER TABLE %s ADD COLUMN %s %s', 
				$this->enquote($this->tablename), $this->enquote($name), $type);

			$this->exec($sql);

			// so is adding an index...
			$idx_name = $this->tablename . '_' . $name;
			$sql = sprintf("CREATE INDEX %s ON %s (%s)", 
				$idx_name, $this->enquote($this->tablename), $name);
			
			$this->exec($sql);
		}
		
		// Drop UNIQUE index for serial_number
		$sql = "ALTER TABLE timemachine DROP index serial_number";
		$this->exec($sql);
		
		// Add INDEX for serial_number
		$sql = "CREATE INDEX timemachine_serial_number ON timemachine (serial_number)";
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
				foreach($this->columns as $name => $type){
					$sql = sprintf('ALTER TABLE %s DROP COLUMN %s', 
						$this->enquote($this->tablename), $this->enquote($name));
					$this->exec($sql);
				}
		}
	}
}
