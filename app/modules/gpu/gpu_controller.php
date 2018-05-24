<?php

use Mr\GPU\GPU;

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
        $db = $this->connectDB();
        $gpuModels = $db::table('gpu')
            ->select(
                $db::raw('count(*) as count'),
                'model'
            )
            ->leftJoin('reportdata', 'gpu.serial_number', '=', 'reportdata.serial_number')
            ->whereNotNull('gpu.model')
            ->groupBy('model');

        $obj->view('json', array('msg' => $gpuModels->get()));
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

        $this->connectDB();
        $gpus = GPU::withoutGlobalScope(\Mr\Scope\MachineGroupScope::class)
            ->where('serial_number', '=', $serial_number)->get();
        $obj->view('json', array('msg' => $gpus));
    }

} // END class Gpu_controller
