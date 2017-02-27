<?php 

/**
 * Fonts module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Fonts_controller extends Module_controller
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
		echo "You've loaded the fonts module!";
	}

	/**
     * Get font names for widget
     *
     * @return void
     * @author tuxudo
     **/
     public function get_fonts()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $fonts = new Fonts_model;
        $obj->view('json', array('msg' => $fonts->get_fonts()));
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
        
        $queryobj = new Fonts_model();
        
        $sql = "SELECT * FROM fonts WHERE serial_number = '$serial_number'";
        
        $fonts_tab = $queryobj->query($sql);

        $fonts = new Fonts_model;
        $obj->view('json', array('msg' => current(array('msg' => $fonts_tab)))); 
    }
		
} // END class Usb_controller
