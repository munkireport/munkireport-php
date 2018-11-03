<?php 

/**
 * Munkireportinfo module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Munkireportinfo_controller extends Module_controller
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
         * @author tuxudo
         **/
        public function index()
        {
                echo "You've loaded the munkireportinfo module!";
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

            //$munkireportinfo = new Munkireportinfo_model;
            //$obj->view('json', array('msg' => $munkireportinfo->retrieve_records($serial_number)));
            
                    $munkireportinfo = new Munkireportinfo_model($serial_number);
        $obj->view('json', array('msg' => $munkireportinfo->rs));
            
        }
} // END class Munkireportinfo_model