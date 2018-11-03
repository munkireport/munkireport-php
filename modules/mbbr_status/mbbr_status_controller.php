<?php
/**
 * mbbr_status_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class mbbr_status_controller extends Module_controller
{
    public function __construct()
    {
      // Store module path
      $this->module_path = dirname(__FILE__) .'/';
      $this->view_path = $this->module_path . 'views/';    }
    /**
     * Default method
     *
     * @author cleavenworth
     **/
    public function index()
    {
        echo "You've loaded the mbbr_status module!";
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
        $mbbr_status = new mbbr_status_model($serial_number);
        $obj->view('json', array('msg' => $mbbr_status->rs));
    }
} // END class mbbr_status_controller
