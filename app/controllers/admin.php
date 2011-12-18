<?php
class admin extends Controller
{
	//===============================================================
	
	function index()
	{
		$client = new Client();
		$data['objects'] = $client->retrieve_many();
		
		$obj = new View();
		$obj->view('client_list',$data);
		
	}
	
	//===============================================================
	
	function test( $msg='Hello World!' )
	{
		
		$client = new Client();
		$data['objects'] = $client->retrieve_many();
		
		$obj = new View();
		$obj->view('overview',$data);
		
	}

	//===============================================================
	
	function resetdb()
	{		
		require(APP_PATH.'helpers/db_helper'.EXT);
		
		reset_db();
		
		redirect('main','Database Initialized!');
	}
	
}