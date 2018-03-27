<?php 

/**
* Teamviewer module class
*
* @package munkireport
* @author tuxudo
**/
class Teamviewer_controller extends Module_controller
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
            echo "You've loaded the teamviewer module!";
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

        $queryobj = new Teamviewer_model();
        
        $sql = "SELECT clientid, clientic, always_online, autoupdatemode, version, update_available, lastmacused, security_adminrights, security_passwordstrength, meeting_username, ipc_port_service, licensetype, is_not_first_run_without_connection, is_not_running_test_connection, had_a_commercial_connection, prefpath, updateversion FROM teamviewer WHERE serial_number = '$serial_number'";
        
        $teamviewer_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $teamviewer_tab))));
    }
} // END class Teamviewer_controller