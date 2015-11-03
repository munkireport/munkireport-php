<?php 

/**
 * Munkireport module class
 *
 * @package munkireport
 * @author 
 **/
class Munkireport_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__) .'/';
		$this->view_path = $this->module_path . 'views/';
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the munkireport module!";
	}

	/**
	 * Show detail information
	 *
	 * @author AvB
	 **/
	function pending()
	{
		if( ! $this->authorized())
		{
			redirect('auth/login');
		}
		
		$data['page'] = '';
		$obj = new View();
		$obj->view('pending', $data, $this->view_path);
	}
	
	/**
	 * Get manifests statistics
	 *
	 *
	 **/
	public function get_manifest_stats()
	{
		$obj = new View();
		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authorized')));
		}
		else
		{
			$mrm = new Munkireport_model();
			$obj->view('json', array('msg' => $mrm->get_manifest_stats()));
		}
	}
	
	/**
	* Get munki versions
	 *
	 *
	 **/
	public function get_versions()
	{
		$obj = new View();
		if( ! $this->authorized())
		{
			$obj->view('json', array('msg' => array('error' => 'Not authorized')));
		}
		else
		{
			$mrm = new Munkireport_model();
			$obj->view('json', array('msg' => $mrm->get_versions()));
		}
	}
	
	
	/**
	 * Get machines with pending installs
	 *	 *
	 * @param integer $hours Number of hours to get stats from
	 **/
	public function get_pending($hours = 24)
	{
		$out = array();
		if( ! $this->authorized())
		{
			$out['error'] = 'Not authorized';
		}
		else {
			$mr = new Munkireport_model;
			foreach($mr->get_pending() AS $rs)
			{
				$out[] = $rs;
			}
		}
		
		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}
	
	/**
	 * Get pending installs
	 *
	 *
	 * @param string $type Type, munki or apple
	 **/
	public function get_pending_installs($type="munki")
	{
		$out = array();
		if( ! $this->authorized())
		{
			$out['error'] = 'Not authorized';
		}
		else
		{
			$mr = new Munkireport_model;
			$from = 24 * 7; // Hours back
			$updates_array = array();

			// Get compression (fixme: we should be able to read this from the model) 
			$compress = function_exists('gzdeflate');
			
			//loop through all the plists
			foreach($mr->get_pending_installs($from) as $obj){

				$report_plist = unserialize( $compress ? gzinflate( $obj->report_plist ) : $obj->report_plist );
				
				if($type == 'apple')
				{
					//loop inside the plist to get the updates and fill the updates_array with the displayed names
					if(isset($report_plist['AppleUpdates']))
					{
						foreach($report_plist['AppleUpdates'] AS $update){
							$updates_array[] = $update['apple_product_name'] . ' ' . $update['version_to_install'];
						}
					}
				}
				else
				{
					//loop inside the plist to get the pending install and fill the pendinginstalls_array with the display names
					foreach($report_plist['ItemsToInstall'] AS $pendinginstall){
						$updates_array[] = $pendinginstall['display_name'] . ' ' . $pendinginstall['version_to_install'];
					}
				}


			}

			//group the updates by count now that the loops are done
			$updates_array = array_count_values($updates_array);
			arsort($updates_array);
			
			foreach ($updates_array AS $name => $count)
			{
				$out[] = array('name' => $name, 'count' => $count);
			}
	
		}
		
		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}
	
	/**
	 * Get statistics
	 *	 *
	 * @param integer $hours Number of hours to get stats from
	 **/
	public function get_stats($hours = 24)
	{
		$out = array();
		if( ! $this->authorized())
		{
			$out['error'] = 'Not authorized';
		}
		else {
			$mr = new Munkireport_model;
			$out = $mr->get_stats($hours);
		}
		
		$obj = new View();
		$obj->view('json', array('msg' => $out));

	}

	
} // END class default_module