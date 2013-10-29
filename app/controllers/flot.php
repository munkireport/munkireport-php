<?php
class flot extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
		{
			die('Authenticate first.'); // Todo: return json?
		}
	} 

	function ip()
	{
		$ip_arr = array();
		
		// See if we're being parsed a request object
		if(array_key_exists('req', $_GET))
		{
			$ip_arr = (array) json_decode($_GET['req']);
		}

		if( ! $ip_arr ) // Empty array, fall back on default ip ranges
		{
			$ip_arr = conf('ip_ranges');
		}
		
		$out = array();
		$reportdata = new Reportdata();

		// Compile SQL
		$cnt = 0;
		$sel_arr = array('COUNT(1) as count');
		foreach ($ip_arr as $key => $value)
		{
			if( is_scalar($value))
			{
				$value = array($value);
			}
			$when_str = '';
			foreach ($value as $k => $v) {
				$when_str .= sprintf(" WHEN remote_ip LIKE '%s%%' THEN 1", $v);
			}
			$sel_arr[] = "SUM(CASE $when_str ELSE 0 END) AS r${cnt}";
			$cnt++;
		}
		$sql = "SELECT " . implode(', ', $sel_arr) . " FROM reportdata";

		// Create Out array
		if($obj = current($reportdata->query($sql)))
		{
			$cnt = $total = 0;
			foreach ($ip_arr as $key => $value)
			{
				$col = 'r' . $cnt++;

				$out[] = array('label' => $key, 'data' => array(array(0,intval($obj->$col))));

				$total += $obj->$col;
			}

			// Add Remaining IP's as other
			$out[] = array('label' => 'Other', 'data' => array(array(0,intval($obj->count - $total))));
				
		}

		echo json_encode($out);

	}

	function hw()
	{
		$out = array();
		$machine = new Machine();
		$sql = 'SELECT machine_name, count(1) as count 
			FROM machine 
			GROUP BY machine_name 
			ORDER BY count DESC';
		$cnt = 0;
		foreach ($machine->query($sql) as $obj)
		{
			$out[] = array('label' => $obj->machine_name, 'data' => array(array($cnt++, intval($obj->count))));
		}

		echo json_encode($out);//TODO: run through view
	}

	function os()
	{
		$out = array();
		$machine = new Machine();
		$sql = "SELECT count(1) as count, os_version 
				FROM machine
				group by os_version 
				ORDER BY os_version ASC";

		$cnt = 0;
		foreach ($machine->query($sql) as $obj)
		{
			$obj->os_version = $obj->os_version ? $obj->os_version : 'Unknown';
			$out[] = array('label' => $obj->os_version, 'data' => array(array(intval($obj->count), $cnt++)));
		}

		echo json_encode($out);//TODO: run through view

	}

	/**
	 * Generate age data for age widget
	 *
	 * @author AvB
	 **/
	function age()
	{
		$out = array();
		$warranty = new Warranty();

		// Time calculations differ between sql implementations
		switch($warranty->get_driver())
		{
			case 'sqlite':
				$agesql = "CAST(strftime('%Y.%m%d', 'now') - strftime('%Y.%m%d', purchase_date) AS INT)";
				break;
			case 'mysql':
				$agesql = "TIMESTAMPDIFF(YEAR,purchase_date,CURDATE())";
				break;
			default: // FIXME for other DB engines
				$agesql = "SUBSTR(purchase_date, 1, 4)";
		}

		$sql = "SELECT count(1) as count, 
				$agesql AS age 
				FROM warranty
				GROUP by age 
				ORDER BY age DESC";
		$cnt = 0;
		foreach ($warranty->query($sql) as $obj)
		{
			$obj->age = $obj->age ? $obj->age : '<1';
			$out[] = array('label' => $obj->age, 'data' => array(array(intval($obj->count), $cnt++)));
		}

		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}

}