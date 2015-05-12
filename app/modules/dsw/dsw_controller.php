<?php 

/**
 * Deploy Studio module class
 *
 * @author Benedicte Emilie Braekken
 **/
class Dsw_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		if( ! $this->authorized())
		{
      // Error message if haven't authenticated
			die('Authenticate first.');
		}

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
		echo "You've loaded the Deploy Studio module!";
	}

	/**
	 * Return json array with Deploy Studio workflow breakdown
	 *
	 * @author Benedicte Emilie Braekken
	 **/	
	function workflow()
	{
		$out = array();
		$deploystudio = new Dsw_model();
		$sql = "SELECT count(1) as count, workflow 
				FROM deploystudio
				GROUP BY workflow
				ORDER BY workflow ASC";

		$workflow_arr = array();
		foreach ($deploystudio->query($sql) as $obj)
		{
			$obj->workflow = $obj->workflow ? $obj->workflow : 'noworkflow';
			$workflow_arr[$obj->workflow] = $obj->count;
		}

		// Convert to flotr array
		$cnt = 0;
		foreach ($workflow_arr as $workflow => $count)
		{
			$workflow = $workflow == '0' ? 'Unknown' : $workflow;
			$out[] = array('label' => $workflow, 'data' => array(array(intval($count), $cnt++)));
		}

		$obj = new View();
		$obj->view('json', array('msg' => $out));
	}
	
} // END class Dsw_controller
