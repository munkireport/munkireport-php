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
        function __construct()
        {
                // Store module path
                $this->module_path = dirname(__FILE__);
        }

        /**
         * Default method
         *
         * @author Calum Hunter
         **/
        function index()
        {
                echo "You've loaded the sccm_status module!";
        }

        
} // END class default_module


