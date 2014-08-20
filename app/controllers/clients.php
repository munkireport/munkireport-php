<?php
class clients extends Controller
{
	function __construct()
	{
		if( ! $this->authorized())
		{
			redirect('auth/login');
		}
	} 

    function index() {
        
        $data['page'] = 'clients';

        $obj = new View();
        $obj->view('client/client_list', $data);
    
    }
	
	// ------------------------------------------------------------------------

	/**
	 * Detail page of a machine
	 *
	 * @param string serial
	 * @return void
	 * @author abn290
	 **/
	function detail($sn='')
	{
		$data = array('serial_number' => $sn);
		$data['scripts'] = array("clients/client_detail.js");

        $obj = new View();

        $machine = new Machine_model($sn);

        // Check if this is an existing entry
        if($machine->id)
        {
        	$obj->view("client/client_detail", $data);
        }
        else
        {
        	$obj->view("client/client_dont_exist", $data);
        }
    	
	}

	// ------------------------------------------------------------------------

	/**
	 * List of machines
	 *
	 * @param string name of view
	 * @return void
	 * @author abn290
	 **/
	function show($view='')
	{
		$data['page'] = 'clients';
		// TODO: Check if view exists
        $obj = new View();
        $obj->view('client/'.$view, $data);
	}
}