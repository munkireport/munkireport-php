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
            return;
        }

        $queryobj = new Fonts_model;
        $fonts_tab = array();
        foreach($queryobj->retrieve_records($serial_number) as $fontEntry) {
            $fonts_tab[] = $fontEntry->rs;
        }

        $obj->view('json', array('msg' => $fonts_tab));
     }
		
} // END class Usb_controller
