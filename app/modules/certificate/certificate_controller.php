<?php

use Mr\Certificate\Certificate;

/**
 * Certificate_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Certificate_controller extends Module_controller
{
    public function __construct()
    {
        $this->module_path = dirname(__FILE__);
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the certificate report module!";
    }

    /**
     * Retrieve data in json format
     *
     * @return void
     * @author
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();
        $db = $this->connectDB();

        if (!$this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $certificates = Certificate::where('serial_number', '=', $serial_number)->get();
        $results = [];

        foreach ($certificates as $certificate) {
            $results[] = Array(
                'pkname' => 'id',
                'tablename' => 'certificates',
                'rs' => $certificate
            );
        }

        $obj->view('json', array('msg' => $results));
    }

    /**
     * Get stats
     *
     **/
    public function get_stats()
    {
        $obj = new View();

        if (!$this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $cert = new Certificate_model;
        $obj->view('json', array('msg' => $cert->get_stats()));
    }

    public function get_certificates()
    {
        $obj = new View();

        if (!$this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }

        $certificate = new Certificate_model;
        $obj->view('json', array('msg' => $certificate->get_certificates()));
    }

} // END class Certificate_controller
