<?php 

/**
 * kb_mouse status module class
 *
 * @package munkireport
 * @author
 **/
class Kb_mouse_controller extends Module_controller
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
	 * @author miqviq
	 **/
	function index()
	{
		echo "You've loaded the kb_mouse module!";
	}

	/**
     * Retrieve data in json format
     *
     **/
 	public function get_data()
	{
		$obj = new View();

        	if( ! $this->authorized())
        	{
        	    $obj->view('json', array('msg' => 'Not authorized'));
		    return;
        	}

        	$kbm = new Kb_mouse_model();
        	$obj->view('json', array('msg' => $kbm->get_data()));
	}
		
} // END class Kb_mouse_module
