<?php 

/**
 * GSX module class
 *
 * @package munkireport
 * @author John Eberle
 **/
class gsx_controller extends Module_controller
{
	function __construct()
	{
		// No authentication, the client needs to get here

		// Store module path
		$this->module_path = dirname(__FILE__);

	}

	function index()
	{
		echo "You've loaded the GSX module!";
	}

	/**
	 * Force recheck GSX
	 *
	 * @return void
	 * @author John Eberle
	 **/
	function recheck_gsx($serial='')
	{
		// Authenticate
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		if(authorized_for_serial($serial))
		{
			$gsx = new gsx_model($serial);
			//$gsx->get_gsx_stats($force=TRUE);
            		$gsx->run_gsx_stats();

		}

		redirect("clients/detail/$serial#tab_gsx-tab");
	}
	
} // END class gsx_module