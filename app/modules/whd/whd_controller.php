<?php 

/**
 * WHD module class
 *
 * @package munkireport
 * @author John Eberle
 **/
class whd_controller extends Module_controller
{
	function __construct()
	{
		// No authentication, the client needs to get here

		// Store module path
		$this->module_path = dirname(__FILE__);

	}

	function index()
	{
		echo "You've loaded the WHD module!";
	}

	/**
	 * Force recheck WHD
	 *
	 * @return void
	 * @author John Eberle
	 **/
	function recheck_whd($serial='')
	{
		// Authenticate
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		if(authorized_for_serial($serial))
		{
			$whd = new whd_model($serial);
			$whd->get_whd_status($force=TRUE);
		}

		redirect("clients/detail/$serial#tab_whd-tab");
	}
	
    /**
	 * Get WHD information for serial_number
	 *
	 * @param string $serial serial number
	 **/
	public function get_data($serial_number = '')
	{
		$obj = new View();

        if( ! $this->authorized())
        {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $whd = new whd_model($serial_number);
        $obj->view('json', array('msg' => $whd->rs));
	}
    
} // END class whd_module