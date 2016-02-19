<?php 
/**
 * power status module class
 *
 * @package munkireport
 * @author
 **/
class munkiprotocol_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}
	/**
	 * Default method
	 *
	 * @author
	 **/
	function index()
	{
		echo "You've loaded the munkiprotocol module!";
	}
	
	/**
	 * Get Munki Protocol Statistics
	 *
	 *
	 **/
	public function get_stats()
	{
		$obj = new View();
		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authenticated')));
			return;
		}
		
		$dm = new munkiprotocol_model();
		$obj->view('json', array('msg' => $dm->get_stats()));
		
	}

	/**
	 * Get conditions
	 *
	 * @return void
	 * @author erikng
	 **/
	function conditions()
	{
		
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json
		}

		$queryobj = new munkiprotocol_model();
		$sql = "SELECT COUNT(CASE WHEN `protocol_status` = 'http' THEN 1 END) AS http,
						COUNT(CASE WHEN `protocol_status` = 'https' THEN 1 END) AS https
			 			FROM munkiprotocol
			 			LEFT JOIN reportdata USING (serial_number)
			 			".get_machine_group_filter();
		$obj = new View();
		$obj->view('json', array('msg' => current($queryobj->query($sql))));

	}
	
} // END class default_module
