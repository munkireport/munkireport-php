<?php 

/**
 * SCCM Agent status module class
 *
 * @package munkireport
 * @author
 **/
class sccm_status_controller extends Module_controller
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
         * @author Calum Hunter
         **/
        public function index()
        {
                echo "You've loaded the sccm_status module!";
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

            $sccm_status_model = new sccm_status_model($serial_number);
            $obj->view('json', array('msg' => $sccm_status_model->rs));
        }
} // END class default_module



