<?php 

/**
 * extensions module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Extensions_controller extends Module_controller
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
	 **/
	function index()
	{
		echo "You've loaded the extensions module!";
	}

	/**
     * Get extension bundle ID for widget
     *
     * @return void
     * @author tuxudo
     **/
     public function get_bundle_ids()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $extension = new Extensions_model;
        $obj->view('json', array('msg' => $extension->get_bundle_ids()));
     }
    
     public function get_codesign()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $extension = new Extensions_model;
        $obj->view('json', array('msg' => $extension->get_codesign()));
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
        
        $queryobj = new Extensions_model();
        
        $sql = "SELECT name, bundle_id, version, path, codesign, executable
                        FROM extensions 
                        WHERE serial_number = '$serial_number'";
        
        $extensions_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $extensions_tab)))); 
    }
		
} // END class Extensions_controller
