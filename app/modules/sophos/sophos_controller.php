<?php
/**
 * Sophos module class
 *
 * @package munkireport
 * @author rickheil
 **/
class Sophos_controller extends Module_controller
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
        echo "You've loaded the Sophos module!";
    }

    /**
     * Get Sophos for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial = '')
    {
        $out = array();
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $prm = new Sophos_model;
            foreach ($prm->retrieve_records($serial) as $sophos) {
                $out[] = $sophos->rs;
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Get installation stats
     *
     * @return void
     * @author rickheil
     **/
    public function get_sophos_install_stats()
    {
        $obj = new View();

        if(! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $sophos_install_stats = new Sophos_model();

        $out = array();
        $out['stats'] = $sophos_install_stats->get_sophos_install_stats();

        $obj->view('json', array('msg' => $out));
    }


    /**
     * Get running stats
     *
     * @return void
     * @author rickheil
     **/
    public function get_sophos_running_stats()
    {
        $obj = new View();

        if(! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $sophos_running_stats = new Sophos_model();

        $out = array();
        $out['stats'] = $sophos_running_stats->get_sophos_running_stats();

        $obj->view('json', array('msg' => $out));

    }


    /**
     * Get product version stats
     *
     * @return void
     * @author rickheil
     **/
    public function get_sophos_product_version_stats()
    {
        $obj = new View();

        if(! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $sophos_product_version_stats = new Sophos_model();

        $out = array();
        $out['stats'] = $sophos_product_version_stats->get_sophos_product_version_stats();

        $obj->view('json', array('msg' => $out));
    }
}
