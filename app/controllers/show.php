<?php
class show extends Controller
{
	function __construct()
	{
		if( ! $this->authorized())
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

	function listing($which = '')
	{
		if($which)
		{
			$data['page'] = 'clients';
			$data['scripts'] = array("clients/client_list.js");
			$view = 'listing/'.$which;
		}
		else
		{
			$data = array('status_code' => 404);
			$view = 'error/client_error';
		}

		$obj = new View();
		$obj->view($view, $data);
	}

	function reports($which = 'default')
	{
		if($which)
		{
			$data['page'] = 'clients';
			$view = 'report/'.$which;
		}
		else
		{
			$data = array('status_code' => 404);
			$view = 'error/client_error';
		}

		$obj = new View();
		$obj->view($view, $data);
	}
		
}