<?php 

/**
 * user_sessions module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class User_sessions_controller extends Module_controller
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
		echo "You've loaded the user_sessions module!";
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

        $queryobj = new User_sessions_model;
        $user_sessions_tab = array();
        foreach($queryobj->retrieve_records($serial_number) as $shareEntry) {
            $user_sessions_tab[] = $shareEntry->rs;
        }

        $obj->view('json', array('msg' => $user_sessions_tab));
     }
		
} // END class User_sessionss_controller
