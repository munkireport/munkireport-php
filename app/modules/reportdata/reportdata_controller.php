<?php 

/**
 * Reportdata module class
 *
 * @package munkireport
 * @author AvB
 **/
class Reportdata_controller extends Module_controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
		{
			die('Authenticate first.'); // Todo: return json?
		}
	}

	function index()
	{
		echo "You've loaded the Reportdata module!";
	}

	/**
	 * Flotr2 interface, returns json with ip address ranges
	 * defined in conf('ip_ranges')
	 * or passed with GET request
	 *
	 * @return void
	 * @author AvB
	 **/
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
		$reportdata = new Reportdata_model();

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

		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}
	
} // END class Reportdata_controller