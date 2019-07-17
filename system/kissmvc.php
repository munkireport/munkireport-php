<?php

use munkireport\models\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

require('kissmvc_core.php');

//===============================================================
// Engine
//===============================================================
class Engine extends KISS_Engine
{
    public function __construct(&$routes, $default_controller, $default_action, $uri_protocol = 'AUTO')
    {
        $GLOBALS[ 'engine' ] = $this;

        parent::__construct($routes, $default_controller, $default_action, $uri_protocol);

    }

    public function requestNotFound($msg = '', $status_code = 404)
    {
        $data = array('status_code' => $status_code, 'msg' => '');

        // Don't show a detailed message when not in debug mode
        conf('debug') && $data['msg'] = $msg;

        $obj = new View();

        $obj->view('error/client_error', $data);

        exit;
    }

    public function get_uri_string()
    {
        return $this->uri_string;
    }
}

//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller
{
    protected $capsule;

    protected function connectDB()
    {
        if(! $this->capsule){
            $this->capsule = new Capsule;

            if( ! $connection = conf('connection')){
                die('Database connection not configured');
            }

            if(has_mysql_db($connection)){
              add_mysql_opts($connection);
            }

            $this->capsule->addConnection($connection);
            $this->capsule->setAsGlobal();
            $this->capsule->bootEloquent();
        }

        return $this->capsule;
    }

    /**
     * Check if there is a valid session
     * and if the person is authorized for $what
     *
     * @return boolean TRUE on authorized
     * @author AvB
     **/
    public function authorized($what = '')
    {
        if (! isset($_SESSION)) {
            ini_set('session.use_cookies', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_path', conf('subdirectory'));
            session_start();
        }

        // Check if we have a valid user
        if (! isset($_SESSION['role'])) {
            return false;
        }

        // Check for a specific authorization item
        if ($what) {
            foreach (conf('authorization', array()) as $item => $roles) {
                if ($what === $item) {
                    // Check if there is a matching role
                    if (in_array($_SESSION['role'], $roles)) {
                        return true;
                    }

                    // Role not found: unauthorized!
                    return false;
                }
            }
        }

        // There is no matching rule, you're authorized!
        return true;
    }

    /**
     * Connect to database when authorized
     *
     * Create a database connection when user is authorized
     *
     * @return type
     * @throws conditon
     **/
    protected function connectDBWhenAuthorized()
    {
        if($this->authorized()){
            $this->connectDB();
        }
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


    public function save()
    {
        // one function to either create or update!
        if ($this->rs[$this->pkname] == '') {
        //primary key is empty, so create
            $this->create();
        } else {
            //primary key exists, so update
            $this->update();
        }

        return $this;
    }

    /**
     * Get SQL partial for trim
     *
     *
     * @param string $string original string
     * @param string $remove characters to remove
     **/
    public function trim($string = '', $remove = ' ')
    {
        switch ($this->get_driver()) {
            case 'sqlite':
                return "TRIM($string, '$remove')";
                break;
            case 'mysql':
                return "TRIM('$remove' FROM $string)";
                break;
        }
    }

    /**
     * Accessor for tablename
     *
     * @return string table name
     **/
    public function get_table_name()
    {
        return $this->tablename;
    }

    /**
     * Accessor for primary key name
     *
     * @return string table name
     **/
    public function get_pkname()
    {
        return $this->pkname;
    }

    /**
     * Get PDO driver name
     *
     * @return string driver
     **/
    public function get_driver()
    {
        return $this->getdbh()->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Get errors
     *
     * @return string errors
     **/
    public function get_errors()
    {
        return $this->errors;
    }

    // ------------------------------------------------------------------------


    /**
     * Run raw query
     *
     * @return array
     * @author
     **/
    public function query($sql, $bindings = array())
    {
        if (is_scalar($bindings)) {
            $bindings=$bindings ? array( $bindings ) : array();
        }
        $stmt = $this->prepare($sql);
        $this->execute($stmt, $bindings);
        $arr=array();
        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
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
    public function exec($sql)
    {
        $dbh = $this->getdbh();

        if ($dbh->exec($sql) === false) {
            $err = $dbh->errorInfo();
            throw new Exception('database error: '.$err[2]);
        }
    }

    /**
     * Retrieve one considering machine_group membership
     * use this instead of retrieveOne
     *
     * @return void
     * @author
     **/
    public function retrieve_record($serial_number, $where = '', $bindings = array())
    {
        if (! authorized_for_serial($serial_number)) {
            return false;
        }

        // Prepend where with serial_number
        $where = $where ? 'serial_number=? AND '.$where : 'serial_number=?';

        // Push serial number in front of the array
        array_unshift($bindings, $serial_number);

        return $this->retrieveOne($where, $bindings);
    }

    // ------------------------------------------------------------------------

    /**
     * Delete one considering machine_group membership
     * use this instead of deleteWhere
     *
     * @return void
     * @author
     **/
    public function delete_record($serial_number, $where = '', $bindings = array())
    {
        if (! authorized_for_serial($serial_number)) {
            return false;
        }

        // Prepend where with serial_number
        $where = $where ? 'serial_number=? AND '.$where : 'serial_number=?';

        // Push serial number in front of the array
        array_unshift($bindings, $serial_number);

        return $this->deleteWhere($where, $bindings);
    }

    // ------------------------------------------------------------------------

    /**
     * Retrieve many considering machine_group membership
     * use this instead of retrieveMany
     *
     * @return void
     * @author
     **/
    public function retrieve_records($serial_number, $where = '', $bindings = array())
    {
        if (! authorized_for_serial($serial_number)) {
            return array();
        }

        // Prepend where with serial_number
        $where = $where ? 'serial_number=? AND '.$where : 'serial_number=?';

        // Push serial number in front of the array
        array_unshift($bindings, $serial_number);

        return $this->retrieveMany($where, $bindings);
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
    public function count($wherewhat = '', $bindings = '')
    {
        $dbh = $this->getdbh();
        if (is_scalar($bindings)) {
            $bindings = $bindings ? array( $bindings ) : array();
        }
        $sql = 'SELECT COUNT(*) AS count FROM '.$this->tablename;
        if ($wherewhat) {
            $sql .= ' WHERE '.$wherewhat;
        }
        $stmt = $dbh->prepare($sql);
        $stmt->execute($bindings);
        if ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
            return $rs->count;
        }
        return 0;
    }

    // ------------------------------------------------------------------------


    /**
     * Store event
     *
     * Store event for this model, assumes we have a serial_number
     *
     * @param string $type Use one of 'danger', 'warning', 'info' or 'success'
     * @param string $msg The message
     **/
    public function store_event($type, $msg, $data = '')
    {
        store_event($this->serial_number, $this->tablename, $type, $msg, $data);
    }

    /**
     * Delete event
     *
     * Delete event for this model, assumes we have a serial_number
     *
     **/
    public function delete_event()
    {
        delete_event($this->serial_number, $this->tablename);
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

    public function get_script($filename = '')
    {
      $this->dumpModuleFile($filename, 'scripts', 'text/plain');
    }
    
    public function js($filename = '')
    {
        $this->dumpModuleFile($filename . '.js', 'js', 'application/javascript');
    }
    
    private function dumpModuleFile($filename, $directory, $content_type)
    {
        // Check if dir exists
        $dir_path = $this->module_path . '/' . $directory . '/';
        if (is_readable($dir_path)) {
        // Get filenames in module dir (just to be safe)
            $files = array_diff(scandir($dir_path), ['..', '.']);
        } else {
            $files = [];
        }

        $file_path = $dir_path . basename($filename);

        if (! in_array($filename, $files) or ! is_readable($file_path)) {
        // Signal to curl that the load failed
            header("HTTP/1.0 404 Not Found");
            printf('File %s is not available', $filename);
            return;
        }

        // Dump the file
        header("Content-Type: application/javascript");
        echo file_get_contents($file_path);
    }
}
