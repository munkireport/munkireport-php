<?php
/**
 * Security module class
 *
 * @package munkireport
 * @author
 **/
class Sentinelone_controller extends Module_controller
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
        echo "You've loaded the sentinelone module!";
    }
    

    /**
     * Get sentinelone for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $s1 = new Sentinelone_model($serial_number);
        $obj->view('json', array('msg' => $s1->rs));
    }


    /**
     * Get active threats statistics
     *
     * @return void
     **/
    public function get_active_threats_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $active_threats = new Sentinelone_model;

                $out = array();
                $out['stats'] = $active_threats->get_active_threats_stats();


        $obj->view('json', array('msg' => $out));
    }    


    /**
     * Get agent running statistics
     *
     * @return void
     **/
    public function get_agent_running_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $agent_running = new Sentinelone_model;

                $out = array();
                $out['stats'] = $agent_running->get_agent_running_stats();


           $obj->view('json', array('msg' => $out));
}    


    /**
     * Get self protection enabled statistics
     *
     * @return void
     **/
    public function get_self_protection_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $self_protection= new Sentinelone_model;

                $out = array();
                $out['stats'] = $self_protection->get_self_protection_stats();


        $obj->view('json', array('msg' => $out));
    }    


    /**
     * Get self protection enabled statistics
     *
     * @return void
     **/
    public function get_enforcing_security_stats()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $enforcing_security= new Sentinelone_model;

                $out = array();
                $out['stats'] = $enforcing_security->get_enforcing_security_stats();


        $obj->view('json', array('msg' => $out));
    }    

    public function get_versions()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $version= new Sentinelone_model;
        $obj->view('json', array('msg' => $version->get_versions()));
    }    

    public function get_mgmt_url()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $mgmt_url= new Sentinelone_model;
        $obj->view('json', array('msg' => $mgmt_url->get_mgmt_url()));
    }    

} // END class default_module
