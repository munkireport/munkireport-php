<?php 

/**
 * FindMyMac manifest status module class
 *
 * @package munkireport
 * @author
 **/
class findmymac_controller extends Module_controller
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
		echo "You've loaded the findmymac module!";
	}
	
	function listing()
	{
		if( ! $this->authorized())
		{
			redirect('auth/login');
		}
		
		$data['page'] = '';
		$obj = new View();
		$obj->view('findmymac_listing', $data, $this->view_path);
	}
	
  /**
 * Get findmymac information for serial_number
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

      $findmymac = new findmymac_model($serial_number);
      $obj->view('json', array('msg' => $findmymac->rs));
}

} // END class default_module