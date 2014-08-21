<?php
/*****************************************************************
Copyright ( c ) 2008-2009 {kissmvc.php version 0.7}
Eric Koh <erickoh75@gmail.com> http://kissmvc.com

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files ( the "Software" ), to deal in the Software without
restriction, including without limitation the rights to use, 
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*****************************************************************/
//===============================================================
// Engine
// Parses HTTP request and routes to appropriate controller+function
//===============================================================
abstract class KISS_Engine 
{
	protected $request_uri_parts=array();
	protected $controller;
	protected $controller_obj;
	protected $action;
	protected $params=array();
	protected $uri_string='';
	
	function __construct( $routes, $default_controller, $default_action, $uri_protocol = 'AUTO')
	{
		if ( $_POST and get_magic_quotes_gpc() )
		{
			$this->clean_post_data();
		}
		
		$this->controller = $default_controller;
		$this->controller=$default_controller;
		$this->action=$default_action;
		
		$this->_fetch_uri_string($uri_protocol);
						
		//Process routes
		$req_uri = ltrim($this->uri_string, '/');
		foreach( $routes as $pat => $route )
		{
			if ( preg_match( '#^'.$pat.'$#', $req_uri ) )
			{
				$req_uri = preg_replace( '#^'.$pat.'$#', $route, $req_uri );
				break;
			}
		}
		
		//Explode URI parts
		$this->request_uri_parts = $req_uri ? explode( '/', $req_uri ) : array();
		
		//Parse request (determine controller/action/params)
		$this->params = array();
		$p = $this->request_uri_parts;
		if ( isset( $p[0] ) && $p[0] ) $this->controller=$p[0];
		if ( isset( $p[1] ) && $p[1] ) $this->action=$p[1];
		if ( isset( $p[2] ) ) $this->params=array_slice( $p, 2 );
		
		//Route request to correct controller/action
		$controller_file = CONTROLLER_PATH.$this->controller.'.php'; //CONTROLLER CLASS FILE
		if ( ! preg_match( '#^[A-Za-z0-9_-]+$#', $this->controller ) or ! file_exists( $controller_file ) )
			$this->request_not_found( 'Controller file not found: '.$controller_file );
		
		//Create controller obj
		require( $controller_file );
		if ( ! class_exists( $this->controller, false ) )
			$this->request_not_found( 'Controller class not found: '.$this->controller );
		$this->controller_obj = new $this->controller;
		
		//call controller function
		if ( ! preg_match( '#^[A-Za-z_][A-Za-z0-9_-]*$#', $this->action ) or ! method_exists( $this->controller_obj, $this->action ) )
			$this->request_not_found( 'Invalid function name: '.$this->action );
		call_user_func_array( array( $this->controller_obj, $this->action ), $this->params );
		
		return $this;
		
	}
	// The following three functions are blatantly copied from CodeIgniter (http://codeigniter.com)
	public function _fetch_uri_string($uri_protocol)
	{
		if (strtoupper($uri_protocol) === 'AUTO')
		{

			// Let's try the REQUEST_URI first, this will work in most situations
			if ($uri = $this->_detect_uri())
			{
				$this->_set_uri_string($uri);
				return;
			}

			// Is there a PATH_INFO variable?
			// Note: some servers seem to have trouble with getenv() so we'll test it two ways
			$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
			if (trim($path, '/') != '' && $path !== '/index.php')
			{
				$this->_set_uri_string($path);
				return;
			}

			// No PATH_INFO?... What about QUERY_STRING?
			$path = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
			if (trim($path, '/') != '')
			{
				$this->_set_uri_string($path);
				return;
			}

			// As a last ditch effort lets try using the $_GET array
			if (is_array($_GET) && count($_GET) === 1 && trim(key($_GET), '/') != '')
			{
				$this->_set_uri_string(key($_GET));
				return;
			}

			// We've exhausted all our options...
			$this->uri_string = '';
			return;
		}

		$uri = strtoupper($uri_protocol);

		if ($uri === 'REQUEST_URI')
		{
			$this->_set_uri_string($this->_detect_uri());
			return;
		}

		$path = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
		$this->_set_uri_string($path);
	}

	// --------------------------------------------------------------------

	/**
	 * Set the URI String
	 *
	 * @param 	string
	 * @return	void
	 */
	public function _set_uri_string($str)
	{
		// Filter out control characters
		//$str = remove_invisible_characters($str, FALSE);

		// If the URI contains only a slash we'll kill it
		$this->uri_string = ($str === '/') ? '' : $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Detects the URI
	 *
	 * This function will detect the URI automatically and fix the query string
	 * if necessary.
	 *
	 * @return	string
	 */
	protected function _detect_uri()
	{
		if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		// Remove multiple slashes
		$uri = preg_replace('#/+#', '/', $_SERVER['REQUEST_URI']);
		$script = preg_replace('#/+#', '/', $_SERVER['SCRIPT_NAME']);

		if (strpos($uri, $script) === 0)
		{
			$uri = substr($uri, strlen($script));
		}
		elseif (strpos($uri, dirname($script)) === 0)
		{
			$uri = substr($uri, strlen(dirname($script)));
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
		if (strncmp($uri, '?/', 2) === 0)
		{
			$uri = substr($uri, 2);
		}

		// Spit on ?
		$parts = preg_split('#\?#i', $uri, 2);
		$uri = $parts[0];

		// Check if we still have an ampersand in the uri
		// jquery will remove the second ? in an ajax call
		if(strpos($uri, '&') !== FALSE)
		{
			$parts = preg_split('#&#i', $uri, 2);
			$uri = $parts[0];
		}
		
		if (isset($parts[1]))
		{
			$_SERVER['QUERY_STRING'] = $parts[1];
			parse_str($_SERVER['QUERY_STRING'], $_GET);
		}
		else
		{
			$_SERVER['QUERY_STRING'] = '';
			$_GET = array();
		}

		if ($uri == '/' OR empty($uri))
		{
			return '/';
		}

		$uri = parse_url($uri, PHP_URL_PATH);

		// Do some final cleaning of the URI and return it
		return str_replace(array('//', '../'), '/', trim($uri, '/'));
	}
	

	//Override this function for your own custom 404 page
	function request_not_found( $msg='' ) 
	{
		header( "HTTP/1.0 404 Not Found" );
		die( '<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>'.$msg.'<p>The requested URL was not found on this server.</p><p>Please go <a href="javascript: history.back( 1 )">back</a> and try again.</p><hr /><p>Powered By: <a href="http://kissmvc.com">KISSMVC</a></p></body></html>' );
	}
	
	function clean_post_data()
	{
		foreach( $_POST as &$vx )
		{
			if(is_string($vx))
			{
				$vx = stripslashes( $vx );
			}
			elseif(is_array($vx))
			{
				foreach($vx as $k => $v)
				{
					$vx[$k] = stripslashes( $v );
				}
			}
		} 
	}
}

//===============================================================
// Controller
// Not sure what it is supposed to do
//===============================================================

abstract class KISS_Controller 
{
	function __construct() 
	{

	}
}

//===============================================================
// View
// For plain .php templates
//===============================================================
abstract class KISS_View 
{
	protected $file='';
	protected $vars=array();

	function __construct( $file='', $vars='' ) 
	{
		if ( $file )
			$this->file = $file;
		if ( is_array( $vars ) )
			$this->vars=$vars;
		return $this;
	}

	function __set( $key, $var ) 
	{
		return $this->set( $key, $var );
	}

	function set( $key, $var ) 
	{
		$this->vars[$key]=$var;
		return $this;
	}

	//for adding to an array
	function add( $key, $var ) 
	{
		$this->vars[$key][]=$var;
	}

	function view($file='', $vars='', $view_path = VIEW_PATH) { //Bluebus addition
		if (is_array($vars))
			$this->vars=array_merge($this->vars,$vars);
		extract($this->vars);
		if ((bool) @ini_get('short_open_tag') === FALSE)
		{
			echo eval($this->short_open($view_path.$file.EXT));
		}
		else
		{
			if( ! @include($view_path.$file.EXT))
			{
				echo '<!-- Could not open '.$view_path.$file.EXT.'-->';
			}
		}
	}
	
	protected function short_open($file)
	{
		return '?>'.str_replace('<?php =', '<?php echo ', preg_replace('/<\?(?!php|xml)/i', '<?php ', file_get_contents($file)));	    
	}

	function fetch( $vars='' ) 
	{
		if ( is_array( $vars ) )
			$this->vars=array_merge( $this->vars, $vars );
		extract( $this->vars );
		ob_start();
		require( $this->file );
		return ob_get_clean();
	}

	function dump( $vars='' ) 
	{
		if ( is_array( $vars ) )
			$this->vars=array_merge( $this->vars, $vars );
		extract( $this->vars );
		require( $this->file );
	}

	static function do_fetch( $file='', $vars='' ) 
	{
		if ( is_array( $vars ) )
			extract( $vars );
		ob_start();
		require( $file );
		return ob_get_clean();
	}

	static function do_dump( $file='', $vars='' ) 
	{
		if ( is_array( $vars ) ) extract( $vars );
		require( $file );
	}

	static function do_fetch_str( $str, $vars='' ) 
	{
		if ( is_array( $vars ) )
			extract( $vars );
		ob_start();
		eval( '?>'.$str );
		return ob_get_clean();
	}

	static function do_dump_str( $str, $vars='' ) 
	{
		if ( is_array( $vars ) )
			extract( $vars );
		eval( '?>'.$str );
	}
}

//===============================================================
// Model/ORM
// Requires a function getdbh() which will return a PDO handler
/*
function getdbh() 
	{
	if ( !isset( $GLOBALS['dbh'] ) )
		try 
	{
			//$GLOBALS['dbh'] = new PDO( 'sqlite:'.APP_PATH.'db/dbname.sqlite' );
			$GLOBALS['dbh'] = new PDO( 'mysql:host=localhost;dbname=dbname', 'username', 'password' );
		} catch ( PDOException $e ) 
	{
			die( 'Connection failed: '.$e->getMessage() );
		}
	return $GLOBALS['dbh'];
}
*/
//===============================================================
abstract class KISS_Model 
{
	protected $dbh = ''; // Database handle
	protected $pkname;
	protected $tablename;
	protected $dbhfnname;
	protected $QUOTE_STYLE='MYSQL'; // valid types are MYSQL, MSSQL, ANSI
	protected $COMPRESS_ARRAY=true;
	public $rs = array(); // for holding all object property variables

	function __construct( $pkname='', $tablename='', $dbhfnname='getdbh', $quote_style='MYSQL', $compress_array=true ) 
	{
		$this->pkname=$pkname; //Name of auto-incremented Primary Key
		$this->tablename=$tablename; //Corresponding table in database
		$this->dbhfnname=$dbhfnname; //dbh function name
		$this->QUOTE_STYLE=$quote_style;
		$this->COMPRESS_ARRAY=function_exists('gzdeflate') && $compress_array;
	}

	function get( $key ) 
	{
		return array_key_exists($key, $this->rs) ? $this->rs[$key] : $key.' not found in '.$this->tablename.' model';
	}

	function set( $key, $val ) 
	{
		if ( array_key_exists($key, $this->rs) )
			$this->rs[$key] = $val;
		return $this;
	}

	function __get( $key ) 
	{
		return $this->get( $key );
	}

	function __set( $key, $val ) 
	{
		return $this->set( $key, $val );
	}

	/**
	 * Get database handle
	 *
	 * @return object PDO instance
	 **/
	protected function getdbh() 
	{
		if ( ! $this->dbh)
		{
			$this->dbh = call_user_func( $this->dbhfnname );
		}
		return $this->dbh;
	}

	protected function enquote( $name ) 
	{
		if ( $this->QUOTE_STYLE=='MYSQL' )
			return '`'.$name.'`';
		elseif ( $this->QUOTE_STYLE=='MSSQL' )
			return '['.$name.']';
		else
			return '"'.$name.'"';
	}

	/**
	 * Prepare statement with error handling
	 *
	 * @return PDOStatement PDO statement object
	 * @author AvB
	 **/
	function prepare($sql, $driver_options = array())
	{
		$dbh = $this->getdbh();

		if( ! $stmt = $dbh->prepare($sql, $driver_options))
        {
            $err = $dbh->errorInfo();
            throw new Exception($sql.' failed with the following error: '.$err[2]);
        }

        return $stmt;
	}

	/**
	 * Execute statement with error handling
	 *
	 * @author AvB
	 **/
	function execute(&$stmt, $params = array())
	{
		// Only execute with params if params passed
		$result = empty($params) ? $stmt->execute() : $stmt->execute($params);

		if( ! $result)
		{
			$err = $stmt->errorInfo();
	        throw new Exception('database error: '.$err[2]);
		}

		return TRUE;
	}

	//Inserts record into database with a new auto-incremented primary key
	//If the primary key is empty, then the PK column should have been set to auto increment
	function create() 
	{
		$dbh=$this->getdbh();
		$pkname=$this->pkname;
		$s1=$s2='';
		foreach ( $this->rs as $k => $v )
			if ( $k!=$pkname or $v ) 
			{
				$s1 .= ', '.$this->enquote( $k );
				$s2 .= ', ?';
			}
		$sql = 'INSERT INTO '.$this->enquote( $this->tablename ).' ( '.substr( $s1, 1 ).' ) VALUES ( '.substr( $s2, 1 ).' )';
		$stmt = $this->prepare( $sql );
		$i=0;
		foreach ( $this->rs as $k => $v )
			if ( $k!=$pkname or $v )
				$stmt->bindValue( ++$i, is_scalar( $v ) ? $v : ( $this->COMPRESS_ARRAY ? gzdeflate( serialize( $v ) ) : serialize( $v ) ) );
		
		$this->execute($stmt);

		if ( ! $stmt->rowCount() )
		{
			return false;
		}
			
		$this->set( $pkname, $dbh->lastInsertId() );
		return $this;
	}

	function retrieve( $pkvalue ) 
	{
		$dbh=$this->getdbh();
		$sql = 'SELECT * FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( $this->pkname ).'=?';
		$stmt = $this->prepare( $sql );
		$stmt->bindValue( 1, ( int )$pkvalue );
		$this->execute($stmt);
		$rs = $stmt->fetch( PDO::FETCH_ASSOC );
		if ( $rs )
			foreach ( $rs as $key => $val )
				if (array_key_exists($key, $this->rs))
					$this->rs[$key] = is_scalar( $this->rs[$key] ) ? $val : unserialize( $this->COMPRESS_ARRAY ? gzinflate( $val ) : $val );
		return $this;
	}

	function update() 
	{
		$dbh=$this->getdbh();
		$s='';
		foreach ( $this->rs as $k => $v )
			$s .= ', '.$this->enquote( $k ).'=?';
		$s = substr( $s, 1 );
		$sql = 'UPDATE '.$this->enquote( $this->tablename ).' SET '.$s.' WHERE '.$this->enquote( $this->pkname ).'=?';
		$stmt = $this->prepare( $sql );
		$i=0;
		foreach ( $this->rs as $k => $v )
			$stmt->bindValue( ++$i, is_scalar( $v ) ? $v : ( $this->COMPRESS_ARRAY ? gzdeflate( serialize( $v ) ) : serialize( $v ) ) );
		$stmt->bindValue( ++$i, $this->rs[$this->pkname] );
		return $this->execute($stmt);
	}

	function delete() 
	{
		$dbh=$this->getdbh();
		$sql = 'DELETE FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( $this->pkname ).'=?';
		$stmt = $this->prepare( $sql );
		$stmt->bindValue( 1, $this->rs[$this->pkname] );
		return $this->execute($stmt);
	}

	//returns true if primary key is a positive integer
	//if checkdb is set to true, this function will return true if there exists such a record in the database
	function exists( $checkdb=false ) 
	{
		if ( ( int )$this->rs[$this->pkname] < 1 ) return false;
		if ( !$checkdb ) return true;
		$dbh=$this->getdbh();
		$sql = 'SELECT 1 FROM '.$this->enquote( $this->tablename ).' WHERE '.$this->enquote( $this->pkname )."='".$this->rs[$this->pkname]."'";
		$result = $dbh->query( $sql )->fetchAll();
		return count( $result );
	}

	function merge( $arr ) 
	{
		if ( ! is_array( $arr ) ) return $this;
		foreach ( $arr as $key => $val )
			if (array_key_exists($key, $this->rs))	$this->rs[$key] = $val;
		return $this;
	}

	function retrieve_one( $wherewhat, $bindings ) 
	{
		$dbh=$this->getdbh();
		if ( is_scalar( $bindings ) )
			$bindings=$bindings ? array( $bindings ) : array();
		$sql = 'SELECT * FROM '.$this->enquote( $this->tablename );
		if ( isset( $wherewhat ) && isset( $bindings ) )
			$sql .= ' WHERE '.$wherewhat;
		$sql .= ' LIMIT 1';
		$stmt = $this->prepare( $sql );
		$this->execute( $stmt, $bindings );
		$rs = $stmt->fetch( PDO::FETCH_ASSOC );
		if ( !$rs )
			return false;
		foreach ( $rs as $key => $val )
			if ( array_key_exists($key, $this->rs) )
				$this->rs[$key] = is_scalar( $this->rs[$key] ) ? $val : unserialize( $this->COMPRESS_ARRAY ? gzinflate( $val ) : $val );
		return $this;
	}

	function retrieve_many( $wherewhat='', $bindings='' ) 
	{
		$dbh = $this->getdbh();
		if ( is_scalar( $bindings ) ) $bindings = $bindings ? array( $bindings ) : array();
		$sql = 'SELECT * FROM '.$this->tablename;
		if ( $wherewhat ) $sql .= ' WHERE '.$wherewhat;
		$stmt = $this->prepare( $sql );
		$this->execute($stmt, $bindings);
		$arr=array();
		$class=get_class( $this );
		while ( $rs = $stmt->fetch( PDO::FETCH_ASSOC ) ) 
		{
			$myclass = new $class();
			foreach ( $rs as $key => $val )
				if ( array_key_exists($key, $myclass->rs) )
					$myclass->rs[$key] = is_scalar( $myclass->rs[$key] ) ? $val : unserialize( $this->COMPRESS_ARRAY ? gzinflate( $val ) : $val );
			$arr[]=$myclass;
		}
		return $arr;
	}

	function select( $selectwhat='*', $wherewhat='', $bindings='', $pdo_fetch_mode=PDO::FETCH_ASSOC ) 
	{
		$dbh=$this->getdbh();
		if ( is_scalar( $bindings ) )
			$bindings=$bindings ? array( $bindings ) : array();
		$sql = 'SELECT '.$selectwhat.' FROM '.$this->tablename;
		if ( $wherewhat )
			$sql .= ' WHERE '.$wherewhat;
		$stmt = $this->prepare( $sql );
		$this->execute( $stmt, $bindings );
		return $stmt->fetchAll( $pdo_fetch_mode );
	}
}