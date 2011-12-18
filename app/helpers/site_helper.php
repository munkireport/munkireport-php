<?php
function url($url='',$fullurl=false) {
  $s=$fullurl ? WEB_DOMAIN : '';
  $s.=WEB_FOLDER.$url;
  return $s;
}

if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = url($uri);
		}
		
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}

function check_db()
{
	// Check if Client table exists
	$dbh = getdbh();
	if( ! $dbh->prepare( "SELECT * FROM 'client' LIMIT 1" ))
	{
		require(APP_PATH.'helpers/db_helper'.EXT);
		if( reset_db() === FALSE )
		{
			die('Could not intialize database');
		}
	}
	
}