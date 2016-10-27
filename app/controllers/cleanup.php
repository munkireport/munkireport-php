<?php
class cleanup extends Controller
{
    public function __construct()
    {

    }
    
    //===============================================================
    
    public function index()
    {
        echo 'Cleanup';
    }

    //===============================================================
    
    public function machine_cleanup()
    {

		//Clean days number
		$clean_days = conf('clean_days');
		
		if($clean_days <= 0) {
			die('Admin:clean: Computer Cleanup Disabled.');
		}
	
        $status = array('status' => 'undefined', 'rowcount' => 0);

        // Don't process these tables
        $skip_tables = array('migration', 'business_unit', 'machine_group', 'munkireport');

		// Delete machine entry from all tables
		$machine = new Machine_model();

		// List tables (unfortunately this is not db-agnostic)
		switch ($machine->get_driver()) {
			case 'sqlite':
				$tbl_query = "SELECT name FROM sqlite_master 
					WHERE type = 'table' AND name NOT LIKE 'sqlite_%'";
				break;
			default:
				// Get database name from dsn string
				if (conf('dbname')) {
					$tbl_query = "SELECT TABLE_NAME AS name FROM information_schema.TABLES 
					WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA='".conf('dbname')."'";
				} else {
					die('Admin:clean: Cannot find database name.');
				}
		}
		
		// Get tables
		$tables = array();
		foreach ($machine->query($tbl_query) as $obj) {
			$tables[] = $obj->name;
		}

		// Get database handle
		$dbh = getdbh();
		$dbh->beginTransaction();

		// Affected rows counter
		$cnt = 0;
		
		try {
			// Delete entries
			foreach ($tables as $table) {
			// Migration has no serial number
				if (in_array($table, $skip_tables)) {
					continue;
				}

				// hash uses serial FIXME
				if ($table == 'hash') {
					$serial = 'serial';
				} else {
					$serial = 'serial_number';
				}
								
				$sql = "DELETE FROM $table WHERE `$serial` IN ((select * from (SELECT munkireport.serial_number FROM munkireport WHERE UNIX_TIMESTAMP(STR_TO_DATE(munkireport.timestamp,'%Y-%m-%d %h:%i:%s')) < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $clean_days day))) as t))";
				if (! $stmt = $dbh->prepare($sql)) {
					die('Prepare '.$sql.' failed');
				}
				
				$stmt->bindValue(1, '');
				$stmt->execute();
				$cnt += $stmt->rowCount();
			}
			
			$table = "munkireport";
			$serial = 'serial_number';
			
			$sql = "DELETE FROM $table WHERE `$serial` IN ((select * from (SELECT munkireport.serial_number FROM munkireport WHERE UNIX_TIMESTAMP(STR_TO_DATE(munkireport.timestamp,'%Y-%m-%d %h:%i:%s')) < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $clean_days day))) as t))";
			if (! $stmt = $dbh->prepare($sql)) {
				die('Prepare '.$sql.' failed');
			}
			
			$stmt->bindValue(1, '');
			$stmt->execute();
			$cnt += $stmt->rowCount();
			
			$dbh->commit();

			// Return status
			$status['status'] = 'success';
			$status['rowcount'] = $cnt;
		} catch (Exception $e) {
			$status['status'] = 'error';
			$status['message'] = $e->getMessage();
		}

        $obj = new View();
        $obj->view('json', array('msg' => $status));
    }
}
