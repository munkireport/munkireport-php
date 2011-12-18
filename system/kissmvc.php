<?php
require('kissmvc_core.php');

//===============================================================
// Engine
//===============================================================
class Engine extends KISS_Engine
{
	function request_not_found( $msg='' ) 
	{
		header( "HTTP/1.0 404 Not Found" );
				
		die( '<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p><p>Please go <a href="javascript: history.back( 1 )">back</a> and try again.</p><hr /><p>Powered By: <a href="http://kissmvc.com">KISSMVC</a></p></body></html>' );
	}
	
}

//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller 
{
	
}

//===============================================================
// Model/ORM
//===============================================================
class Model extends KISS_Model
{

}

//===============================================================
// View
//===============================================================
class View extends KISS_View
{
	
}