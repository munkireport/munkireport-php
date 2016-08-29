<?php 

/**
 * kb_mouse status module class
 *
 * @package munkireport
 * @author
 **/
class kb_mouse_controller extends Module_controller
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
    public function get_data($serial = '')
    {
		$out = array();
		if( ! $this->authorized())
		{
			$out['error'] = 'Not authorized';
		}
		else
		{
			$prm = new kb_mouse_model;
			foreach($prm->retrieve_records($serial) as $kb_mouse)
			{
				$out[] = $kb_mouse->rs;
			}
		}
		
		$obj = new View();
		$obj->view('json', array('msg' => $out));
    }
		
} // END class default_module
