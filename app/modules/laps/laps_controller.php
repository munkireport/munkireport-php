<?php

/**
 * laps_controller class
 *
 * @package laps
 * @author tuxudo
 **/

class Laps_controller extends Module_controller
{
	function __construct()
	{
		$this->module_path = dirname(__FILE__);
	}

    /**
    * Default method
    *
    * @author tuxudo
    **/
    function index()
    {
        echo "You've loaded the laps module!";
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

        $laps_tab = new Laps_model($serial_number);
        $obj->view('json', array('msg' => $laps_tab->rs));
    }
    
    /**
    * Returns password expiration and date set in JSON format
    * Largely stolen from report.php :P
    *
    * @author tuxudo
    **/ 
    public function checkin()
    {
        $obj = new View();

        // Verify passphrase
        if (isset($_POST['passphrase'])) {
            $this->group = passphrase_to_group($_POST['passphrase']);
        }

        if ($auth_list = conf('client_passphrases')) {
            if (! is_array($auth_list)) {
                $obj->view('json', array('msg' => "conf['client_passphrases'] should be an array"));
                return;
            }

            if (! isset($_POST['passphrase'])) {
                $obj->view('json', array('msg' => "passphrase is required but missing"));
                return;
            }

            if (! in_array($_POST['passphrase'], $auth_list)) {
                $obj->view('json', array('msg' => 'passphrase "'.$_POST['passphrase'].'" not accepted'));
                return;
            }
        }
        
        // Check if we have a serial and data
        if (! isset($_POST['serial'])) {
            $obj->view('json', array('msg' => "Serial is missing"));
            return;
        }

        if (! trim($_POST['serial'])) {
            $obj->view('json', array('msg' => "Serial is empty"));
            return;
        }
        
        $serial_number = $_POST['serial'];
        $sql = "SELECT dateexpires, dateset, script_enabled, days_till_expiration, alpha_numeric_only, keychain_remove, pass_length
                    FROM laps 
                    WHERE serial_number = '$serial_number'";
        
        $queryobj = new Laps_model();
        $laps_return = $queryobj->query($sql);
        $obj->view('json', array('msg' => $laps_return[0]));
        
    }
} // END class Laps_controller
