<?php
class update extends Controller
{
	function __construct()
	{
		check_db();
	} 
	
	//===============================================================
	
	function preflight()
	{
		if($_POST)
		{
			$_POST['req'] = 'preflight';
			$_POST['timestamp'] = date('Y-m-d H:i:s');
			$_POST['remote_ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST['runstate'] = 'in progress';
						
			$client = new Client($_POST['serial']);
						
			if($client->id == '')
			{
				$client->merge($_POST)->create();
			}
			else
			{
				$client->merge($_POST)->update();
			}
		}
	}

	//===============================================================
	
	function postflight()
	{
		if($_POST)
		{
			$_POST['req'] = 'postflight';
			$_POST['timestamp'] = date('Y-m-d H:i:s');
			$_POST['remote_ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST['runstate'] = 'done';
						
			$client = new Client($_POST['serial']);
			
			$client->merge($_POST);
									
			if( isset($_POST['base64bz2report']))
			{
				$client->update_report(bzdecompress(base64_decode($_POST['base64bz2report'])));
			}
			
			if($client->id == '')
			{
				$client->create();
			}
			else
			{
				$client->update();
			}
		}
		else
		{
			echo 'postflight';
		}		
	}
	
	//===============================================================
	
	function report_broken_client()
	{
		if($_POST)
		{
			$_POST['req'] = 'report_broken_client';
			$_POST['timestamp'] = date('Y-m-d H:i:s');
			$_POST['remote_ip'] = $_SERVER['REMOTE_ADDR'];
			$_POST['runstate'] = 'broken client';
						
			$client = new Client($_POST['serial']);
						
			if($client->id == '')
			{
				$client->merge($_POST)->create();
			}
			else
			{
				$client->merge($_POST)->update();
			}
		}
		
			
	}
	
	
}