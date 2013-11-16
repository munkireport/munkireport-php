<?php 

/**
 * Warranty module class
 *
 * @package munkireport
 * @author AvB
 **/
class Warranty_controller extends Module_controller
{
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
		if( ! isset($_SESSION['user']))
		{
			redirect('auth/login');
		}

		$warranty = new Warranty($serial);
		$warranty->check_status($force=TRUE);
		redirect("clients/detail/$serial");
	}

	
} // END class Warranty_module