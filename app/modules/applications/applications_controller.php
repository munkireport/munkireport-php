<?php 

/**
 * Applicatoins module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Applications_controller extends Module_controller
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
		echo "You've loaded the applications module!";
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
        }
        
        $queryobj = new Applications_model();
        
        $sql = "SELECT name, path, lastModified, obtained_from, runtime_environment, version, info, signed_by, has64BitIntelCode
                        FROM applications
                        WHERE serial_number = '$serial_number'";
        
        $applications_tab = $queryobj->query($sql);

        $applications = new Applications_model;
        $obj->view('json', array('msg' => current(array('msg' => $applications_tab)))); 
    }
		
} // END class Applications_controller
