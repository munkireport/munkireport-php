<?php
class clients extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
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

        $obj = new View();
    	$obj->view("client/client_detail", $data);
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

	/**
	 * Force recheck warranty TODO: maybe move somewhere else?
	 *
	 * @return void
	 * @author 
	 **/
	function recheck_warranty($serial='')
	{
		$warranty = new Warranty($serial);
		$warranty->check_status($force=TRUE);
		redirect("clients/detail/$serial");
	}


}