<?php 

/**
 * Comment_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Comment_controller extends Module_controller
{
	function __construct()
	{
        if( ! $this->authorized())
        {
            $obj->view('json', array('msg' => 'Not authorized'));
        }

        $this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the comment module!";
	}

    /**
     * Create a comment
     *
     **/
    function create()
    {
        $out = array();

        // Sanitize
        $serial_number = post('serial_number');
        $section = post('section');
        $text = post('text');
        if( $serial_number AND $section AND $text)
        {
            $comment = new Comment_model;
            $comment->serial_number = $serial_number;
            $comment->section = $section;
            $comment->text = $text;
            $comment->user = $_SESSION['user'];
            $comment->timestamp = time();
            $comment->save();
            $out['status'] = 'success';
        }
        else
        {
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
    function retrieve($serial_number = '', $section = '')
    {
        $obj = new View();

        $where = $section ? 'section=?' : '';
        $bindings = $section ? array($section) : array();

        $comment = new Comment_model;
        $obj->view('json', array('msg' => $comment->retrieve_records($serial_number, $where, $bindings)));
    }

    /**
     * Update comment
     *
     **/
    function update()
    {

    }

    /**
     * Delete comment
     *
     **/
    function delete()
    {

    }
    



} // END class Certificate_controller