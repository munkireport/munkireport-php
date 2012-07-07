<?php

// Munkireport version (last number is number of commits)
$GLOBALS['version'] = '0.8.3.57';

//===============================================
// Uncaught Exception Handling
//===============================================s
function uncaught_exception_handler($e)
{
  ob_end_clean(); //dump out remaining buffered text
  $vars['message']=$e;
  die(View::do_fetch(APP_PATH.'errors/exception_uncaught.php',$vars));
}

function custom_error($msg='') 
{
	$vars['msg']=$msg;
	die(View::do_fetch(APP_PATH.'errors/custom_error.php',$vars));
}

//===============================================
// Database
//===============================================
function getdbh()
{
	if ( ! isset($GLOBALS['dbh']))
	{
		try
		{
			$GLOBALS['dbh'] = new PDO(
				$GLOBALS['db']['dsn'], 
				$GLOBALS['db']['user'], 
				$GLOBALS['db']['pass'], 
				$GLOBALS['db']['opts']
				);
		}
		catch (PDOException $e)
		{
			die('Connection failed: '.$e->getMessage());
		}
	}
	return $GLOBALS['dbh'];
}

//===============================================
// Autoloading for Business Classes
//===============================================
// Assumes Model Classes start with capital letters and Libraries start with lower case letters
function __autoload( $classname )
{
	$a=$classname[0];
	if ( $a >= 'A' && $a <='Z' ) require_once( APP_PATH.'models/'.$classname.'.php' );
	else require_once( APP_PATH.'libraries/'.$classname.'.php' );  
}

function url($url='', $fullurl = FALSE)
{
  $s = $fullurl ? WEB_HOST : '';
  $s .= WEB_FOLDER.($url && INDEX_PAGE ? INDEX_PAGE.'/' : INDEX_PAGE) . ltrim($url, '/');
  return $s;
}

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

function humanreadablesize($bytes, $decimals = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f %sB", $bytes / pow(1024, $factor), $factor?@$sz[$factor]:' ');
}
