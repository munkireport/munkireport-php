<?php

use Mr\HomebrewInfo\HomebrewInfo;

/**
 * homebrew_info module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Homebrew_info_controller extends Module_controller
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
		echo "You've loaded the homebrew_info module!";
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
        $homebrew_info = HomebrewInfo::where('serial_number', '=', $serial_number)->first();
        $obj->view('json', array('msg' => $homebrew_info));

    }
		
} // END class Homebrew_info_controller
