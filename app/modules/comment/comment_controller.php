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
    function save()
    {
        $out = array();

        // Sanitize
        $serial_number = post('serial_number');
        $section = post('section');
        $text = post('text');
        $html = post('html');
        if( $serial_number AND $section AND $text)
        {
            if( authorized_for_serial($serial_number))
            {
                $comment = new Comment_model;
                $comment->retrieve_record($serial_number, 'section=?', array($section));
                $comment->serial_number = $serial_number;
                $comment->section = $section;
                $comment->text = $text;
                $comment->html = $html;
                $comment->user = $_SESSION['user'];
                $comment->timestamp = time();
                $comment->save();

                $out['status'] = 'saved';
            }
            else
            {
                $out['status'] = 'error';
                $out['msg'] = 'Not authorized for this serial';
            }
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
        $out = array();

        $where = $section ? 'section=?' : '';
        $bindings = $section ? array($section) : array();

        $comment = new Comment_model;
        if($section)
        {
            if($comment->retrieve_record($serial_number, $where, $bindings))
            {
                $out = $comment->rs;
            }
        }
        else
        {
            foreach($comment->retrieve_records($serial_number, $where, $bindings) AS $obj)
            {
                $out[] = $obj->rs;
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
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