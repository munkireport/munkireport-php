<?php

/**
 * Devtools module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Devtools_controller extends Module_controller
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
		echo "You've loaded the devtools module!";
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
            
        $devtools = new Devtools_model($serial_number);
        $obj->view('json', array('msg' => $devtools->rs));
            
     }
} // END class Devtools_controller
