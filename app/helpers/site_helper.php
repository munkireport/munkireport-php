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

class ErrorPage {
    // Some functions for displaying error pages
    
    static function error400($msg='') {
        header("HTTP/1.0 400 Bad Request");
        die('<html><head><title>400 Bad Request</title></head><body><h1>Bad Request</h1><p>'.$msg.'<hr /></body></html>');
    }
    
    static function error401($msg='') {
        header("HTTP/1.0 401 Unauthorized");
        die('<html><head><title>401 Unauthorized</title></head><body><h1>Unauthorized</h1><p>'.$msg.'<hr /></body></html>');
    }
    
    static function error403($msg='') {
        header("HTTP/1.0 403 Forbidden");
        die('<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>'.$msg.'<hr /></body></html>');
    }
    
    static function error404($msg='') {
        header("HTTP/1.0 404 Not Found");
        die('<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>'.$msg.'<p>The requested URL was not found on this server.</p><hr /></html>');
    }
    
}