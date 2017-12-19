<?php

/**
 * GSX module class
 *
 * @package munkireport
 * @author John Eberle
 **/
class Gsx_controller extends Module_controller
{
    public function __construct()
    {
        // No authentication, the client needs to get here

        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    public function index()
    {
        echo "You've loaded the GSX module!";
    }

    /**
     * Force recheck GSX
     *
     * @return void
     * @author John Eberle
     **/
    public function recheck_gsx($serial = '')
    {
        // Authenticate
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        if (authorized_for_serial($serial)) {
            $gsx = new gsx_model($serial);
            //$gsx->get_gsx_stats($force=TRUE);
                    $gsx->run_gsx_stats();
        }

        redirect("clients/detail/$serial#tab_gsx-tab");
    }
    
    /**
     * Get GSX information for widget
     *
     * @return void
     * @author John Eberle (tuxudo)
     **/
    public function get_GSX_Support_Stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $gsx = new gsx_model;
        $obj->view('json', array('msg' =>$gsx->getGSXSupportStats()));
    }
    
    /**
     * Get GSX information for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $gsx = new gsx_model($serial_number);
        $obj->view('json', array('msg' => $gsx->rs));
    }
} // END class Gsx_module
