<?php

/**
 * homebrew module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Detectx_controller extends Module_controller
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
		echo "You've loaded the detectx module!";
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

        $queryobj = new Detectx_model;
        $detectx_tab = array();
        foreach($queryobj->retrieve_records($serial_number) as $detectxEntry) {
            $detectx_tab[] = $detectxEntry->rs;
        }

        $obj->view('json', array('msg' => $detectx_tab));
	}

} // END class Detectx_controller
