<?php

/**
 * Crashplan_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Crashplan_controller extends Module_controller
{
    public function __construct()
    {
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the Crashplan module!";
    }
    
    public function listing()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
        
        $data['page'] = '';
        $obj = new View();
        $obj->view('crashplan_listing', $data, $this->view_path);
    }
    
    /**
     * Get  stats
     *
     * @return void
     * @author
     **/
    public function get_stats($hours = 24)
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $model = new Crashplan_model;
        $obj->view('json', array('msg' => $model->get_stats($hours)));
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

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
        }
        $out = array();
        $model = new Crashplan_model;
        foreach ($model->retrieve_records($serial_number) as $record) {
            $out[] = $record->rs;
        }
        $obj->view('json', array('msg' => $out));
    }
} // END class Crashplan_controller
