<?php

use Mr\Homebrew\Homebrew;

/**
 * homebrew module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Homebrew_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author tuxudo
	 *
	 **/
	function index()
	{
		echo "You've loaded the homebrew module!";
	}
    
	/**
     * Retrieve data in json format
     *
     **/
	public function get_data($serial_number = '')
	{
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $this->connectDB();
        $homebrew_apps = Homebrew::where('serial_number', '=', $serial_number)->get();

        $obj->view('json', array('msg' => $homebrew_apps));
	}
		
} // END class Homebrew_controller
