<?php

class Migration_security_add_firewall_state extends Model
{
	protected $columnname = 'firewall_state';

	public function __construct()
	{
		parent::__construct();
		$this->tablename = 'security';
		$this->idx[] = array('firewall_state');
	}


	public function up()
	{
		// Get database handle
		$dbh = $this->getdbh();

		// First add the table
		$dbh->beginTransaction();
		$sql = sprintf(
			'ALTER TABLE %s ADD COLUMN %s INTEGER DEFAULT NULL',
			$this->enquote($this->tablename),
			$this->enquote($this->columnname)
		);

		$this->exec($sql);
		$dbh->commit();

		// Add index too
        	switch ($this->get_driver()) {
            		case 'sqlite':
                		// In SQLite we just use 'IF NOT EXISTS'
                		$sql = 'CREATE INDEX IF NOT EXISTS %s ON %s (%s)';
                		break;

            		case 'mysql':
                		$sql = 'CREATE INDEX %s ON %s (%s)';

                		// Look up existing indexes
                		$indexes = $this->query("SELECT index_name
                                                FROM INFORMATION_SCHEMA.STATISTICS
                                                WHERE table_schema = DATABASE()
                                                AND table_name = '".$this->get_table_name()."'");

                		foreach ($indexes as $obj) {
                    			foreach ($this->idx as $k => $idx_data) {
                        			if ($obj->index_name == $this->get_index_name($idx_data)) {
                        	    			// If index exists, unset from index
                            				unset($this->idx[$k]);
                        			}
                    			}
                		}
                	break;
            	default:
                	throw new Exception("UNKNOWN DRIVER", 1);
        	}

        	// Call set indexes()
        	$this->set_indexes($sql);
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
