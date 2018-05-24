<?php

use Mr\USB\USB;

/**
 * USB status module class
 *
 * @package munkireport
 * @author miqviq
 **/
class Usb_controller extends Module_controller
{
	
	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author miqviq
	 *
	 **/
	function index()
	{
		echo "You've loaded the usb module!";
	}

	/**
     * Get USB device names for widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
     public function get_usb_devices()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $db = $this->connectDB();
        $usbDevices = $db::table('usb')
             ->select(
                 $db::raw('count(*) as count'),
                 'name'
             )
             ->leftJoin('reportdata', 'usb.serial_number', '=', 'reportdata.serial_number')
             ->whereNotNull('usb.name')
             ->orderBy('count', 'DESC')
             ->groupBy('name');
        $obj->view('json', array('msg' => $usbDevices->get()));
     }
    
     /**
     * Get USB device types for widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
     public function get_usb_types()
     {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

         $db = $this->connectDB();
         $usbTypes = $db::table('usb')
             ->select(
                 $db::raw('count(*) as count'),
                 'type'
             )
             ->leftJoin('reportdata', 'usb.serial_number', '=', 'reportdata.serial_number')
             ->whereNotNull('usb.type')
             ->orderBy('count', 'DESC')
             ->groupBy('type');
         $obj->view('json', array('msg' => $usbTypes->get()));
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

        $this->connectDB();
        $usbDevices = USB::withoutGlobalScope(\Mr\Scope\MachineGroupScope::class)
            ->where('serial_number', '=', $serial_number)->get();
        $obj->view('json', array('msg' => $usbDevices));
    }
		
} // END class Usb_controller
