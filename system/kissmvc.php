<?php
require('kissmvc_core.php');

//===============================================================
// Engine
//===============================================================
class Engine extends KISS_Engine
{
	function __construct( &$routes, $default_controller, $default_action, $uri_protocol = 'AUTO')
    {
        $GLOBALS[ 'engine' ] = $this;

        parent::__construct( $routes, $default_controller, $default_action, $uri_protocol);

    }

	function request_not_found( $msg='', $status_code = 404 ) 
	{
		$data = array('status_code' => $status_code, 'msg' => '');

		// Don't show a detailed message when not in debug mode
		conf('debug') && $data['msg'] = $msg;
			
		$obj = new View();

		$obj->view('error/client_error', $data);

		exit;
	}

	function get_uri_string()
    {
        return $this->uri_string;
    }
	
}

//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller 
{
	/**
	 * Check if there is a valid session
	 * and if the person is authorized for $what
	 *
	 * @return boolean TRUE on authorized
	 * @author AvB
	 **/
	function authorized($what = '')
	{
		if( ! isset($_SESSION))
		{
			ini_set('session.use_cookies', 1);
			ini_set('session.use_only_cookies', 1);
			ini_set('session.cookie_path', conf('subdirectory'));
			session_start();
		}

		// Check if we have a valid user
		if( ! isset($_SESSION['role']))
		{
			return FALSE;
		}

		// Check for a specific authorization item
		if($what)
		{
			foreach(conf('authorization', array()) as $item => $roles)
			{
				if($what === $item)
				{
					// Check if there is a matching role
					if( in_array($_SESSION['role'], $roles))
					{
						return TRUE;
					}

					// Role not found: unauthorized!
					return FALSE;
				}
			}
		}

		// There is no matching rule, you're authorized!
		return TRUE;
	}
}

//===============================================================
// Model/ORM
//===============================================================
class Model extends KISS_Model
{
    protected $rt = array(); // Array holding types
    protected $idx = array(); // Array holding indexes

	// Schema version, increment in child model when creating a db migration
    protected $schema_version = 0;

    // Errors
    protected $errors = '';


	function save()
	{
        // one function to either create or update!
        if ($this->rs[$this->pkname] == '')
        {
            //primary key is empty, so create
            $this->create();
        }
        else
        {
            //primary key exists, so update
            $this->update();
        }
		
		return $this;
    }

    /**
     * Get schema version
     *
     * @return integer schema version number
     **/
    function get_version()
    {
    	return $this->schema_version;
    }

    /**
     * Accessor for tablename
     *
     * @return string table name
     **/
    function get_table_name()
    {
    	return $this->tablename;
    }

    /**
     * Accessor for primary key name
     *
     * @return string table name
     **/
    function get_pkname()
    {
    	return $this->pkname;
    }

    /**
     * Get PDO driver name
     *
     * @return string driver
     **/
    function get_driver()
    {
    	return $this->getdbh()->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Get errors
     *
     * @return string errors
     **/
    function get_errors()
    {
    	return $this->errors;
    }

     /**
     * Get indexes
     *
     * @return string errors
     **/
    function get_indexes()
    {
    	return $this->idx;
    }

     /**
     * Get types
     *
     * @return string errors
     **/
    function get_types()
    {
    	return $this->rt;
    }

	// ------------------------------------------------------------------------


    /**
	 * Run raw query
	 *
	 * @return array
	 * @author 
	 **/
	function query($sql, $bindings=array())
	{
		if ( is_scalar( $bindings ) )
			$bindings=$bindings ? array( $bindings ) : array();
		$stmt = $this->prepare( $sql );
		$this->execute( $stmt, $bindings );
		$arr=array();
		while ( $rs = $stmt->fetch( PDO::FETCH_OBJ ) )
		{
			$arr[] = $rs;
		}
		return $arr;
	}

	// ------------------------------------------------------------------------

	/**
	 * Exec statement with error handling
	 *
	 * @author AvB
	 **/
	function exec($sql)
	{
		$dbh = $this->getdbh();

		if( $dbh->exec($sql) === FALSE)
		{
			$err = $dbh->errorInfo();
	        throw new Exception('database error: '.$err[2]);
		}
	}

	/**
	 * Retrieve one considering machine_group membership
	 * use this instead of retrieve_one
	 *
	 * @return void
	 * @author 
	 **/
	function retrieve_record($serial_number, $where = '', $bindings = array())
	{
		if( ! authorized_for_serial($serial_number))
		{
			return FALSE;
		}

		// Prepend where with serial_number
		$where = $where ? 'serial_number=? AND '.$where : 'serial_number=?';

		// Push serial number in front of the array
		array_unshift($bindings, $serial_number);
		
		return $this->retrieve_one($where, $bindings);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Delete one considering machine_group membership
	 * use this instead of delete_where
	 *
	 * @return void
	 * @author 
	 **/
	function delete_record($serial_number, $where = '', $bindings = array())
	{
		if( ! authorized_for_serial($serial_number))
		{
			return FALSE;
		}

		// Prepend where with serial_number
		$where = $where ? 'serial_number=? AND '.$where : 'serial_number=?';

		// Push serial number in front of the array
		array_unshift($bindings, $serial_number);
		
		return $this->delete_where($where, $bindings);
	}

	// ------------------------------------------------------------------------

	/**
	 * Retrieve many considering machine_group membership
	 * use this instead of retrieve_many
	 *
	 * @return void
	 * @author 
	 **/
	function retrieve_records($serial_number, $where = '', $bindings = array())
	{
		if( ! authorized_for_serial($serial_number))
		{
			return array();
		}

		// Prepend where with serial_number
		$where = $where ? 'serial_number=? AND '.$where : 'serial_number=?';

		// Push serial number in front of the array
		array_unshift($bindings, $serial_number);

		return $this->retrieve_many($where, $bindings);
	}


	// ------------------------------------------------------------------------

	/**
	 * Count records
	 *
	 * @param string where
	 * @param mixed bindings
	 * @return void
	 * @author abn290
	 **/
	function count( $wherewhat='', $bindings='' )
	{
		$dbh = $this->getdbh();
		if ( is_scalar( $bindings ) ) $bindings = $bindings ? array( $bindings ) : array();
		$sql = 'SELECT COUNT(*) AS count FROM '.$this->tablename;
		if ( $wherewhat ) $sql .= ' WHERE '.$wherewhat;
		$stmt = $dbh->prepare( $sql );
		$stmt->execute( $bindings );
		if ( $rs = $stmt->fetch( PDO::FETCH_OBJ ) ) 
		{
			return $rs->count;
		}
		return 0;
	}

	/**
	 * Get database type of value
	 *
	 * Returns INTEGER, VARCHAR(255), REAL or MEDIUMBLOB
	 *
	 * @return string database type
	 * @author AvB
	 **/
	function get_type($val = '')
	{
		return is_int($val) ? 'INTEGER' : (is_string($val) ? 'VARCHAR(255)' : (is_float($val) ? 'REAL' : 'MEDIUMBLOB'));
	}

	/**
	 * Get compound index name
	 *
	 * @return string index name
	 * @author 
	 **/
	function get_index_name($idx_data = array())
	{
		return $this->tablename . '_' . join('_', $idx_data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Create table
	 * 
	 * Create table based on $this->rs array
	 * and $this->rt array
	 *
	 * @param array assoc array with optional type strings
	 * @return boolean TRUE on success, FALSE if failed
	 * @author bochoven
	 **/
	function create_table()
	{
		// Check if we instantiated this table before
		if(isset($GLOBALS['tables'][$this->tablename]))
		{
			return TRUE;
		}

		// Check if table exists and is up-to-date
		try
		{
			$dbh = $this->getdbh();
		
			$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

			// Check if table exists
			$this->prepare( "SELECT * FROM ".$this->enquote($this->tablename)." LIMIT 1" );

			// Existing table, is it up-to date?
			if (conf('allow_migrations'))
        	{
        		if ($this->get_schema_version() !== $this->schema_version)
        		{
        			try
        			{
        				require_once(conf('application_path').'helpers/database_helper.php');

	        			migrate($this);

	        			$model_name = get_class($this);
	        			alert('Migrated '.$model_name.' to version '.$this->schema_version);
        			}
        			catch(Exception $e)
        			{
        				error("Migration error: $this->tablename: ".$e->getMessage());

        				// Rollback any open transaction
        				try { $dbh->rollBack(); } catch (Exception $e2) {}
        			}
        			
        		}
        	}
		}
		catch (Exception $e)
		{
			// If the prepare fails, the table does not exist.

			// Get columns
			$columns = array();
			foreach($this->rs as $name => $val)
			{
				// Determine type automagically
				$type = $this->get_type($val);
				
				// Or set type from type array
				$columns[$name] = isset($this->rt[$name]) ? $this->rt[$name] : $type;
			}
			
			// Set primary key
			$columns[$this->pkname] = 'INTEGER PRIMARY KEY';

			// Table options, override per driver
			$tbl_options = '';
			
			// Driver specific options
			switch($this->get_driver())
			{
				case 'sqlite':
					$columns[$this->pkname] .= ' AUTOINCREMENT';
					break;
				case 'mysql':
					$columns[$this->pkname] .= ' AUTO_INCREMENT';
					$tbl_options = conf('mysql_create_tbl_opts');
					break;
			}
			
			// Compile columns sql
            $sql = '';
			foreach($columns as $name => $type)
			{
				$sql .= $this->enquote($name) . " $type,";
			}
			$sql = rtrim($sql, ',');

			try
			{

				$dbh->exec(sprintf("CREATE TABLE %s (%s) %s", $this->enquote($this->tablename), $sql, $tbl_options));

				// Set indexes
				$this->set_indexes();

				// Store schema version in migration table
				$migration = new Migration($this->tablename);
				$migration->version = $this->schema_version;
				$migration->save();

				alert("Created table '$this->tablename' version $this->schema_version");

			}
			catch (Exception $e)
			{
				$dbh->exec('DROP TABLE '.$this->enquote($this->tablename));
				error("Create table '$this->tablename' failed: " . $e->getMessage());
				return FALSE;
			}
            
		}

        // Store this table in the instantiated tables array
        $GLOBALS['tables'][$this->tablename] = $this->tablename;

        // Create table succeeded
        return TRUE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Set indexes for this table
	 *
	 * @param string optional provide alternative create index
	 * @author bochoven
	 **/
	function set_indexes($sql = "CREATE INDEX %s ON %s (%s)")
	{
		$dbh = $this->getdbh();
		
		foreach($this->idx as $idx_data)
		{
			// Create name
			$idx_name = $this->get_index_name($idx_data);
			$this->exec(sprintf($sql, $idx_name, $this->enquote($this->tablename), join(',', $idx_data)));
		}
		
	}

	/**
	 * Get schema version in the database
	 *
	 * @return void
	 * @author 
	 **/
	function get_schema_version()
	{
		// Get schema versions
		if( ! isset($GLOBALS['schema_versions']))
		{
			// Store schema versions in global, other models may need it too
			$GLOBALS['schema_versions'] = array();

			$migration = new Migration;
			foreach( $migration->query('SELECT table_name, version FROM migration') AS $obj)
			{
				$GLOBALS['schema_versions'][$obj->table_name] = $obj->version;
			}
		}

		return array_key_exists($this->tablename, $GLOBALS['schema_versions']) ?
			intval($GLOBALS['schema_versions'][$this->tablename]) : 0;
	}
}

//===============================================================
// View
//===============================================================
class View extends KISS_View
{
	
}

/**
 * Module controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Module_controller extends Controller
{
	
	// Module, override in child object
	protected $module_path;

	function get_script($name='')
	{
		// Check if script dir exists
		if( is_readable($this->module_path . '/scripts/'))
		{
			// Get scriptnames in module scripts dir (just to be safe)
			$scripts = array_diff(scandir($this->module_path . '/scripts/'), array('..', '.'));
		}
		else
		{
			$scripts = array();
		}
		
		$script_path = $this->module_path . '/scripts/' . basename($name);

		if( ! in_array($name, $scripts) OR ! is_readable($script_path))
		{
			// Signal to curl that the load failed
			header("HTTP/1.0 404 Not Found");
			printf('Script %s is not available', $name);
			return;
		}

		// Dump the file
		header("Content-Type: text/plain");
		echo file_get_contents($script_path);
	}

}