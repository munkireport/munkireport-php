<?php

/**
 * Tag_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Tag_controller extends Module_controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            $obj = new View();
            $obj->view('json', array('msg' => array('error' =>'Not authorized')));
            die();
        }

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
        echo "You've loaded the Tag module!";
    }
    
    public function listing()
    {
        $data['page'] = '';
        $obj = new View();
        $obj->view('tag_listing', $data, $this->view_path);
    }
    

    /**
     * Create a Tag
     *
     **/
    public function save()
    {
        $out = array();

        // Sanitize
        $serial_number = post('serial_number');
        $tag = post('tag');
        if ($serial_number and $tag) {
            if (authorized_for_serial($serial_number)) {
                $tagm = new Tag_model;
                $tagm->retrieve_record($serial_number, 'tag=?', array($tag));
                $tagm->serial_number = $serial_number;
                $tagm->tag = $tag;
                $tagm->user = $_SESSION['user'];
                $tagm->timestamp = time();
                $tagm->save();

                $out = $tagm->rs;
            } else {
                $out['status'] = 'error';
                $out['msg'] = 'Not authorized for this serial';
            }
        } else {
            $out['status'] = 'error';
            $out['msg'] = 'Missing data';
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Retrieve data in json format
     *
     **/
    public function retrieve($serial_number = '')
    {
        $out = array();

        $Tag = new Tag_model;
        foreach ($Tag->retrieve_records($serial_number) as $obj) {
            $out[] = $obj->rs;
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Delete Tag
     *
     **/
    public function delete($serial_number = '', $id = -1)
    {
        $out = array();
        
        $where = $id ? 'id=?' : '';
        $bindings = $id ? array($id) : array();

        $Tag = new Tag_model;
        if (! $Tag->delete_record($serial_number, $where, $bindings)) {
            $out['status'] = 'error';
        } else {
            $out['status'] = 'success';
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
    
    /**
     * Get all defined tags
     *
     * Returns a JSON array with all defined tags, used for typeahead
     *
     **/
    public function all_tags($add_count = false)
    {
        $Tag = new Tag_model;
        $obj = new View();
        $obj->view('json', array('msg' => $Tag->all_tags($add_count)));
    }
} // END class Certificate_controller
