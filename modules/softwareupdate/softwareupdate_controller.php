<?php 

/**
 * Softwareupdate module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Softwareupdate_controller extends Module_controller
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
                echo "You've loaded the softwareupdate module!";
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
            
            $softwareupdate = new Softwareupdate_model($serial_number);
            $obj->view('json', array('msg' => $softwareupdate->rs));
            
        }
} // END class Softwareupdate_model