<?php 

/**
 * GPU module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Gpu_controller extends Module_controller
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
		echo "You've loaded the gpu module!";
	}

	/**
     * Get GPU models for widget
     *
     * @return void
     * @author tuxudo
     **/
     public function get_gpu_models()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $gpu = new Gpu_model;
        $obj->view('json', array('msg' => $gpu->get_gpu_models()));
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
        
        $queryobj = new Gpu_model();
        
        $sql = "SELECT * FROM gpu WHERE serial_number = '$serial_number'";
        
        $gpu_tab = $queryobj->query($sql);

        $gpu = new Gpu_model;
        $obj->view('json', array('msg' => current(array('msg' => $gpu_tab)))); 
    }
		
} // END class Gpu_controller
