<?php 

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

        $queryobj = new Homebrew_model;
        $homebrew_tab = array();
        foreach($queryobj->retrieve_records($serial_number) as $brewEntry) {
            $homebrew_tab[] = $brewEntry->rs;
        }

        $obj->view('json', array('msg' => $homebrew_tab));
	}
		
} // END class Homebrew_controller
