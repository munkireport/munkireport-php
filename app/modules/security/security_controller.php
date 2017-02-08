<?php
/**
 * Security module class
 *
 * @package munkireport
 * @author
 **/
class Security_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }
    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the security module!";
    }
    
    /**
     * Get security for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial = '')
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $prm = new Security_model;
            foreach ($prm->retrieve_records($serial) as $security) {
                $out[] = $security->rs;
            }
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Get SIP statistics
     *
     * @return void
     * @author rickheil
     **/
    public function get_sip_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $sip_report = new Security_model;

                $out = array();
                $out['stats'] = $sip_report->get_sip_stats();


        $obj->view('json', array('msg' => $out));
    }    

    /**
     * Get Gatekeeper statistics
     *
     * @return void
     * @author rickheil
     **/
    public function get_gatekeeper_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $sip_report = new Security_model;

                $out = array();
                $out['stats'] = $sip_report->get_gatekeeper_stats();


        $obj->view('json', array('msg' => $out));
    }

    /**
     * Get firmware password statistics
     *
     * @return void
     * @author rickheil
     **/
    public function get_firmwarepw_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $sip_report = new Security_model;

                $out = array();
                $out['stats'] = $sip_report->get_firmwarepw_stats();


        $obj->view('json', array('msg' => $out));
    }
} // END class default_module
