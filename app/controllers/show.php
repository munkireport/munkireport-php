<?php
class show extends Controller
{
	function __construct()
	{
		if( ! isset($_SESSION['user']))
		{
			redirect('auth/login');
		}
	} 

	function index()
	{
		$data = array();
		$obj = new View();

		// Check if there's a custom dashboard
		if( file_exists(VIEW_PATH.'dashboard/custom_dashboard'.EXT))
		{
			$obj->view('dashboard/custom_dashboard', $data);
		}
		else
		{
			$obj->view('dashboard/dashboard', $data);
		}
		
	}

	function listing($which)
	{
		$data['page'] = 'clients';
		$data['scripts'] = array("clients/client_list.js");
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