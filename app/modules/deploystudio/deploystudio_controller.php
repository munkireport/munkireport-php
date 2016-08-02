<?php 

/**
 * DeployStudio module class
 *
 * @package munkireport
 * @author tuxudo (John Eberle)
 **/
class deploystudio_controller extends Module_controller
{
	function __construct()
	{
		// No authentication, the client needs to get here
		// Store module path
		$this->module_path = dirname(__FILE__);

	}

	function index()
	{
		echo "You've loaded the deploystudio module!";
	}

	/**
	 * Force recheck from DeployStudio
	 *
	 * @return void
	 * @author tuxudo (John Eberle)
	 **/
	function recheck_deploystudio($serial='')
	{
		// Authenticate
		if( ! $this->authorized())
		{
			die('Authenticate first.'); // Todo: return json?
		}

		if(authorized_for_serial($serial))
		{
			$deploystudio = new deploystudio_model($serial);
			$deploystudio->run_deploystudio_stats();
		}

		redirect("clients/detail/$serial#tab_deploystudio-tab");
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

        $deploystudio = new deploystudio_model($serial_number);
        $obj->view('json', array('msg' => $deploystudio->rs));
	}
		
} // END class deploystudio_module