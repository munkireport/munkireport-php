<?php

// Munkireport version (last number is number of commits)
$GLOBALS['version'] = '2.0.2.415';

// Return version without commit count
function get_version()
{
	return preg_replace('/(.*)\.\d+$/', '$1', $GLOBALS['version']);
}

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
				conf('pdo_dsn'),
				conf('pdo_user'),
				conf('pdo_pass'),
				conf('pdo_opts')
				);
		}
		catch (PDOException $e)
		{
			die('Connection failed: '.$e->getMessage());
		}
		
		// Store database name in config array
		if(preg_match('/.*dbname=([^;]+)/', conf('pdo_dsn'), $result))
		{
			$GLOBALS['conf']['dbname'] = $result[1];
		}
	}
	return $GLOBALS['dbh'];
}

//===============================================
// Autoloading for Business Classes
//===============================================
// module classes end with _model
function __autoload( $classname )
{
	// Switch to lowercase filename for models
	$classname = strtolower($classname);

	if(substr($classname, -4) == '_api')
	{
		require_once( APP_PATH.'modules/'.substr($classname, 0, -4).'/api'.EXT );
	}
	elseif(substr($classname, -6) == '_model')
	{
		$module = substr($classname, 0, -6);
		require_once( APP_PATH."modules/${module}/${module}_model".EXT );
	}
	else
	{
		require_once( APP_PATH.'models/'.$classname.EXT );
	}
}

function url($url='', $fullurl = FALSE)
{
  $s = $fullurl ? conf('webhost') : '';
  $s .= conf('subdirectory').($url && INDEX_PAGE ? INDEX_PAGE.'/' : INDEX_PAGE) . ltrim($url, '/');
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
