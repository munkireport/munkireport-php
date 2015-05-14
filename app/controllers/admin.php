<?php
class admin extends Controller
{
	function __construct()
	{
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		if( ! $this->authorized('global'))
		{
			redirect('unit');
		}

	} 
	
	
	//===============================================================
	
	function index()
	{
		echo 'Admin';
	}

	//===============================================================

	/**
	 * Retrieve business units information
	 *
	 * @return void
	 * @author 
	 **/
	function get_business_units()
	{
		$business_unit = new Business_unit;
		$machine_group = new Machine_group;
		$out = array();
		foreach($business_unit->select() as $unit)
		{

			$out[$unit->unitid][$unit->property] = $unit->value;
		}
	}

	//===============================================================

	/**
	 * Save Business Unit
	 *
	 * @return void
	 * @author 
	 **/
	function save_business_unit()
	{
		$out = array();

		if( ! $_POST)
		{
			$out['error'] = 'No data';
		}
		elseif(isset($_POST['unitid']))
		{
			$business_unit = new Business_unit;

			$unitid = $_POST['unitid'];

			// Check if new unit
			if($unitid == 'new')
			{
				$unitid = $business_unit->get_max_unitid() + 1;
			}

			$out['unitid'] = $unitid;

			foreach($_POST as $property => $val)
			{
				
				// Skip unitid
				if($property == 'unitid')
				{
					continue;
				}

				if(is_scalar($val))
				{
					$business_unit->id = '';
					$business_unit->retrieve_one('unitid=? AND property=?', array($unitid, $property));
					$business_unit->unitid = $unitid;
					$business_unit->property = $property;
					$business_unit->value = $val;
					$business_unit->save();
					$out[$property] = $val;
				}
				else
				{
					//array data (machine_groups, users)
				}
			}
			
		}
		else
		{
			$out['error'] = 'Unitid missing';
		}

		$obj = new View();
        $obj->view('json', array('msg' => $out));

	}

	//===============================================================

	/**
	 * remove_business_unit
	 *
	 * @return void
	 * @author 
	 **/
	function remove_business_unit($unitid='')
	{
		$out = array();

		if($unitid !== ''){
			$bu = new Business_unit;
			$out['success'] = $bu->delete_where('unitid=?', $unitid);
		}

		$obj = new View();
        $obj->view('json', array('msg' => $out));


	}

	//===============================================================


	/**
	 * Return BU data for unitid or all units if unitid is empty
	 *
	 * @return void
	 * @author 
	 **/
	function get_bu_data($unitid = "")
	{
        $obj = new View();
        $bu = new Business_unit;
        $obj->view('json', array('msg' => $bu->all($unitid)));
	}

	//===============================================================

	/**
	 * Return Machinegroup data for groupid or all groups if groupid is empty
	 *
	 * @return void
	 * @author 
	 **/
	function get_mg_data($groupid = "")
	{
        $obj = new View();
        $mg = new Machine_group;
        $obj->view('json', array('msg' => $mg->all($groupid)));
	}

	//===============================================================

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	function show($which = '')
	{
		if($which)
		{
			$data['page'] = 'clients';
			$data['scripts'] = array("clients/client_list.js");
			$view = 'admin/'.$which;
		}
		else
		{
			$data = array('status_code' => 404);
			$view = 'error/client_error';
		}

		$obj = new View();
		$obj->view($view, $data);
	}

	//===============================================================
	
	function delete_machine($serial_number='')
	{
		
		$status = array('status' => 'undefined', 'rowcount' => 0);

		if( ! $this->authorized('delete_machine'))
		{
			$status['status'] = 'unauthorized';
		}
		else
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
			$status['status'] = 'success';
			$status['rowcount'] = $cnt;
		}



		$obj = new View();
		$obj->view('json', array('msg' => $status));
		
	}
	
}