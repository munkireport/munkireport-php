<?php 

/**
 * Warranty module class
 *
 * @package munkireport
 * @author tuxudo (John Eberle)
 **/
class Ds_controller extends Module_controller
{
	function __construct()
	{
		// No authentication, the client needs to get here

		// Store module path
		$this->module_path = dirname(__FILE__);

	}

	function index()
	{
		echo "You've loaded the ds (DeployStudio) module!";
	}

	/**
	 * Force recheck from DeployStudio
	 *
	 * @return void
	 * @author tuxudo (John Eberle)
	 **/
	function recheck_ds($serial='')
	{
		// Authenticate
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		if(authorized_for_serial($serial))
		{
			$ds = new Ds_model($serial);
			$ds->run_ds_stats();
		}

		redirect("clients/detail/$serial#tab_ds-tab");
	}

    /**
	 * Get DeployStudio information for serial_number
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

        $ds = new Ds_model($serial_number);
        $obj->view('json', array('msg' => $ds->rs));
	}
		
} // END class Ds_module