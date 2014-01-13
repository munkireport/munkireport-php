<?php
class admin extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
		{
			redirect('auth/login');
		}
	} 
	
	
	//===============================================================
	
	function index()
	{
		echo 'Admin';
	}
	
	
	//===============================================================
	
	function delete_machine($serial_number='')
	{
		// Delete machine entry from all tables
		$machine = new Machine_model();

		// List tables (unfortunately this is not db-agnostic)
		switch($machine->get_driver())
		{
			case 'sqlite':
				$tbl_query = "SELECT name FROM sqlite_master 
					WHERE type = 'table' AND name NOT LIKE 'sqlite_%'";
				break;
			default:
				// Get database name from dsn string
				if(conf('dbname'))
				{
					$tbl_query = "SELECT TABLE_NAME AS name FROM information_schema.TABLES 
					WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA='".conf('dbname')."'";
				}
				else
				{
					die('Admin:delete_machine: Cannot find database name.');
				}
		}
		
		// Get tables
		$tables = array();
		foreach ($machine->query($tbl_query) as $obj)
		{
			$tables[] = $obj->name;
		}

		// Get database handle
		$dbh = getdbh();
		$dbh->beginTransaction();

		// Affected rows counter
		$cnt = 0;

		// Delete entries
		foreach($tables as $table)
		{
			// Migration has no serial number
			if ($table == 'migration')
			{
				continue;
			}

			// hash and inventoryitem use serial FIXME
			if($table == 'hash' OR $table == 'inventoryitem')
			{
				$serial = 'serial';
			}
			else
			{
				$serial = 'serial_number';
			}
			
			$sql = "DELETE FROM $table WHERE `$serial`=?";
			if( ! $stmt = $dbh->prepare( $sql ))
			{
				die('Prepare '.$sql.' failed');
			}
			$stmt->bindValue( 1, $serial_number );
			$stmt->execute();
			$cnt += $stmt->rowCount();
		}

		$dbh->commit();

		// Return status
		$status = array('status' => 'success', 'rowcount' => $cnt);
		$obj = new View();
		$obj->view('json', array('msg' => $status));
		
	}
	
}