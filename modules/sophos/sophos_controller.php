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
        if (! $this->authorized()) {
            $out['error'] = 'Not authorized';
        } else {
            $prm = new Sophos_model;
            foreach ($prm->retrieve_records($serial) as $sophos) {
                $out = $sophos->rs;
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
        if(! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $sophos_product_version_stats = new Sophos_model();

        $sql = "SELECT count(1) as count, product_version
            FROM sophos
            LEFT JOIN reportdata USING (serial_number)
            ".get_machine_group_filter()."
            GROUP BY product_version
            ORDER BY product_version DESC";

        foreach ($sophos_product_version_stats->query($sql) as $obj) {
            $obj->product_version = $obj->product_version ? $obj->product_version : '0';
            $out[] = array('label' => $obj->product_version, 'count' => intval($obj->count));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }


    /**
     * Get engine version stats
     *
     * @return void
     * @author rickheil
     **/
    public function get_sophos_engine_version_stats()
    {
        if(! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $sophos_engine_version_stats = new Sophos_model();

        $sql = "SELECT count(1) as count, engine_version
            FROM sophos
            LEFT JOIN reportdata USING (serial_number)
            ".get_machine_group_filter()."
            GROUP BY engine_version
            ORDER BY engine_version DESC";

        foreach ($sophos_engine_version_stats->query($sql) as $obj) {
            $obj->engine_version = $obj->engine_version ? $obj->engine_version : '0';
            $out[] = array('label' => $obj->engine_version, 'count' => intval($obj->count));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }

    /**
     * Get virus data version stats
     *
     * @return void
     * @author rickheil
     **/
    public function get_sophos_virus_data_version_stats()
    {
        if(! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $sophos_virus_data_version_stats = new Sophos_model();

        $sql = "SELECT count(1) as count, virus_data_version
            FROM sophos
            LEFT JOIN reportdata USING (serial_number)
            ".get_machine_group_filter()."
            GROUP BY virus_data_version
            ORDER BY virus_data_version DESC";

        foreach ($sophos_virus_data_version_stats->query($sql) as $obj) {
            $obj->virus_data_version = $obj->virus_data_version ? $obj->virus_data_version : '0';
            $out[] = array('label' => $obj->virus_data_version, 'count' => intval($obj->count));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }

    /**
     * Get user interface version stats
     *
     * @return void
     * @author rickheil
     **/
    public function get_sophos_user_interface_version_stats()
    {
        if(! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $sophos_user_interface_version_stats = new Sophos_model();

        $sql = "SELECT count(1) as count, user_interface_version
            FROM sophos
            LEFT JOIN reportdata USING (serial_number)
            ".get_machine_group_filter()."
            GROUP BY user_interface_version
            ORDER BY user_interface_version DESC";

        foreach ($sophos_user_interface_version_stats->query($sql) as $obj) {
            $obj->user_interface_version = $obj->user_interface_version ? $obj->user_interface_version : '0';
            $out[] = array('label' => $obj->user_interface_version, 'count' => intval($obj->count));
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));

    }
}
