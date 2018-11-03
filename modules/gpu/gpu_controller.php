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
            return;
        }

        $queryobj = new Gpu_model;
        $gpu_tab = array();
        foreach($queryobj->retrieve_records($serial_number) as $gpuEntry){
            $gpu_tab[] = $gpuEntry->rs;
        }

        $obj->view('json', array('msg' => $gpu_tab));
    }

} // END class Gpu_controller
