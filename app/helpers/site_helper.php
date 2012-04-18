<?php
function url($url='', $fullurl = FALSE)
{
  $s = $fullurl ? WEB_HOST : '';
  $s .= WEB_FOLDER.($url && INDEX_PAGE ? INDEX_PAGE.'/' : INDEX_PAGE).$url;
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

function humanreadablesize($kbytes) {
    $units['KB'] = pow(2,10);
    $units['MB'] = pow(2,20);
    $units['GB'] = pow(2,30);
    $units['TB'] = pow(2,40);
    foreach ($units as $suffix => $limit)
    {
        if ($kbytes <= $limit)
        {
            return strval(round($kbytes/($limit/pow(2,10)), 1)) . ' ' . $suffix;
        }
    }
}

