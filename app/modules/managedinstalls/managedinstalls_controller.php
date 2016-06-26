<?php 

/**
 * managedinstalls class
 *
 * @package munkireport
 * @author 
 **/
class managedinstalls_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
		$this->view_path = dirname(__FILE__) . '/views/';
	}
	
	  /**
	 * Get managedinstalls information for serial_number
	 *
	 * @param string $serial serial number
	 **/
	public function get_data($serial_number = '')
	{
		$out = array();
        if( ! $this->authorized())
        {
          $out['error'] = 'Not authorized';
        }
        else
        {
          $model = new Managedinstalls_model;
          foreach($model->retrieve_records($serial_number) as $prefs)
          {
            $out[] = $prefs->rs;
          }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
	}
	
	/**
	 * undocumented function summary
	 *
	 * Undocumented function long description
	 *
	 * @param type var Description
	 * @return {11:return type}
	 */
	public function listing($value='')
	{
		if( ! $this->authorized())
		{
			redirect('auth/login');
		}
		$data['page'] = 'clients';
		$data['scripts'] = array("clients/client_list.js");
        $obj = new View();
        $obj->view('managed_installs_list', $data, $this->view_path);
	}

} // END class default_module
