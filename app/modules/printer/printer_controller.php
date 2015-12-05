<?php 

/**
 * Printer module class
 *
 * @package munkireport
 * @author 
 **/
class Printer_controller extends Module_controller
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
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the printer module!";
	}
	
	/**
	 * Get printers for serial_number
	 *
	 * @param string $serial serial number
	 **/
	public function get_data($serial = '')
	{

		$out = array();
		if( ! $this->authorized())
		{
			$out['error'] = 'Not authorized';
		}
		else
		{
			$prm = new Printer_model;
			foreach($prm->retrieve_records($serial) as $printer)
			{
				$out[] = $printer->rs;
			}
		}
		
		$obj = new View();
		$obj->view('json', array('msg' => $out));
	}

	
} // END class default_module