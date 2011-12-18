<?php
class admin extends Controller
{
	function __construct()
	{
		check_db();
		die("authentication needed"); // TODO: implement auth
	} 
	
	
	//===============================================================
	
	function index()
	{
		echo 'Admin';
	}
	
	
	//===============================================================
	
	function resetdb()
	{		
		require(APP_PATH.'helpers/db_helper'.EXT);
		
		reset_db();
		
		redirect();
	}
	
}