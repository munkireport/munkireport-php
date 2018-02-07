<?php
/**
 * Security module class
 *
 * @package munkireport
 * @author
 **/
class Mdm_status_controller extends Module_controller
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
     * @author eholtam
     **/
    public function index()
    {
        echo "You've loaded the mdm_status module!";
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
            $prm = new Mdm_status_model;
            foreach ($prm->retrieve_records($serial) as $mdm_status) {
                $out[] = $mdm_status->rs;
            }
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    public function get_mdm_enrollment_type_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $mdm_type = new Mdm_status_model;
        $obj->view('json', array('msg' => $mdm_type->get_mdm_enrollment_type_stats()));
    }

    public function get_mdm_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $mdm_status = new Mdm_status_model;
        $obj->view('json', array('msg' => $mdm_status->get_mdm_stats()));
    }

} // END class default_module
