<?php 

/**
 * Messages module class
 *
 * @package munkireport
 * @author AvB
 **/
class Messages_controller extends Module_controller
{
	function __construct()
	{
		if( ! $this->authorized())
		{
			$out['items'] = array();
			$out['reload'] = TRUE;
			$out['error'] = 'Session expired: please login';
			$obj = new View();
			$obj->view('json', array('msg' => $out));

			die(); 
		}
	}

	function index()
	{
		echo "You've loaded the Messages module!";
	}

	/**
	 * Get messages
	 *
	 * @author AvB
	 **/
	function get($type = 'all')
	{
		$queryobj = new Messages_model();
		$queryobj = new Reportdata_model();
		$twentyfour = time() - 60 * 60 * 24;
		$out['items'] = array();
		$out['error'] = '';
		$sql = "SELECT m.serial_number, module, type, msg, m.timestamp 
				FROM messages m LEFT JOIN reportdata USING (serial_number) 
				WHERE m.timestamp > $twentyfour 
				ORDER BY m.timestamp DESC";


		foreach($queryobj->query($sql) as $obj)
		{
			$out['items'][] = $obj;
		}

		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}

	
} // END class Messages_controller