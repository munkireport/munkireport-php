<?php 
/**
 * munkiinfo status module class
 *
 * @package munkireport
 * @author
 **/
class munkiinfo_controller extends Module_controller
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
		echo "You've loaded the munkiinfo module!";
	}
	
	/**
	 * Get Munki Protocol Statistics
	 *
	 *
	 **/
	public function get_protocol_stats()
	{
		$obj = new View();
		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authenticated')));
			return;
		}
		
		$mim = new munkiinfo_model();
		$obj->view('json', array('msg' => $mim->get_protocol_stats()));
		
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

		$queryobj = new munkiinfo_model();
		$sql = "SELECT COUNT(CASE WHEN `munkiprotocol` = 'http' THEN 1 END) AS http,
						COUNT(CASE WHEN `munkiprotocol` = 'https' THEN 1 END) AS https,
						COUNT(CASE WHEN `munkiprotocol` = 'localrepo' THEN 1 END) AS localrepo
			 			FROM munkiinfo
			 			LEFT JOIN reportdata USING (serial_number)
			 			".get_machine_group_filter();
		$obj = new View();
		$obj->view('json', array('msg' => current($queryobj->query($sql))));

	}
	
} // END class default_module
