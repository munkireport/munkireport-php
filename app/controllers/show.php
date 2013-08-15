<?php
class show extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
		{
			redirect('auth/login/clients');
		}
	} 

	function index()
	{
		$data = array();
		$obj = new View();
		$obj->view('dashboard/dashboard', $data);

	}

	function listing($which)
	{
		$data['page'] = 'clients';
		$obj = new View();
		$obj->view('listing/'.$which, $data);
	}
	
	function reports($which = 'default')
	{		
		$data['page'] = 'reports';
		$obj = new View();
		$obj->view('report/'.$which, $data);
	}
	
}