<?php 

/**
 * Warranty module class
 *
 * @package munkireport
 * @author AvB
 **/
class Warranty_controller extends Module_controller
{
	function __construct()
	{
		// No authentication, the client needs to get here

		// Store module path
		$this->module_path = dirname(__FILE__);

	}

	function index()
	{
		echo "You've loaded the warranty module!";
	}

	/**
	 * Force recheck warranty
	 *
	 * @return void
	 * @author AvB
	 **/
	function recheck_warranty($serial='')
	{
		// Authenticate
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		$warranty = new Warranty_model($serial);
		$warranty->check_status($force=TRUE);
		redirect("clients/detail/$serial");
	}

	/**
	 * Get estimate_manufactured_date
	 *
	 * @return void
	 * @author 
	 **/
	function estimate_manufactured_date($serial_number='')
	{
		// Authenticate
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		require_once(conf('application_path') . "helpers/warranty_helper.php");
		$out = array('date' => estimate_manufactured_date($serial_number));
		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}

	/**
	 * Generate age data for age widget
	 *
	 * @author AvB
	 **/
	function age()
	{
		// Authenticate
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		$out = array();
		$warranty = new Warranty_model();

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

	
} // END class Warranty_module