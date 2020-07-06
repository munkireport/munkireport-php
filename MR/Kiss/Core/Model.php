<?php
namespace MR\Kiss\Core;


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
abstract class Model
{
    protected $dbh = ''; // Database handle
    public $pkname;
    public $tablename;
    protected $dbhfnname;
    protected $QUOTE_STYLE='MYSQL'; // valid types are MYSQL, MSSQL, ANSI
    protected $COMPRESS_ARRAY=true;
    public $rs = array(); // for holding all object property variables

    public function __construct($pkname = '', $tablename = '', $dbhfnname = 'getdbh', $quote_style = 'MYSQL', $compress_array = true)
    {
        $this->pkname=$pkname; //Name of auto-incremented Primary Key
        $this->tablename=$tablename; //Corresponding table in database
        $this->dbhfnname=$dbhfnname; //dbh function name
        $this->QUOTE_STYLE=$quote_style;
        $this->COMPRESS_ARRAY=function_exists('gzdeflate') && $compress_array;
    }

    public function get($key)
    {
        return array_key_exists($key, $this->rs) ? $this->rs[$key] : $key.' not found in '.$this->tablename.' model';
    }

    public function set($key, $val)
    {
        if (array_key_exists($key, $this->rs)) {
            $this->rs[$key] = $val;
        }
        return $this;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $val)
    {
        return $this->set($key, $val);
    }

    /**
     * Get database handle
     *
     * @return object PDO instance
     **/
    public function getdbh()
    {
        if (! $this->dbh) {
            $this->dbh = call_user_func($this->dbhfnname);
        }
        return $this->dbh;
    }

    public function enquote($name)
    {
        if ($this->QUOTE_STYLE=='MYSQL') {
            return '`'.$name.'`';
        } elseif ($this->QUOTE_STYLE=='MSSQL') {
            return '['.$name.']';
        } else {
            return '"'.$name.'"';
        }
    }

    /**
     * Prepare statement with error handling
     *
     * @return PDOStatement PDO statement object
     * @author AvB
     **/
    public function prepare($sql, $driver_options = array())
    {
        $dbh = $this->getdbh();

        if (! $stmt = $dbh->prepare($sql, $driver_options)) {
            $err = $dbh->errorInfo();
            throw new Exception($sql.' failed with the following error: '.$err[2]);
        }

        return $stmt;
    }

    /**
     * Execute statement with error handling
     *
     * @param $stmt \PDOStatement
     * @author AvB
     **/
    public function execute(&$stmt, $params = array())
    {
        // Only execute with params if params passed
        $result = empty($params) ? $stmt->execute() : $stmt->execute($params);

        if (! $result) {
            $err = $stmt->errorInfo();
            throw new Exception('database error: '.$err[2]);
        }

        return true;
    }

    //Inserts record into database with a new auto-incremented primary key
    //If the primary key is empty, then the PK column should have been set to auto increment
    public function create()
    {
        $dbh = $this->getdbh();
        $pkname=$this->pkname;
        $s1=$s2='';
        foreach ($this->rs as $k => $v) {
            if ($k!=$pkname or $v) {
                $s1 .= ', '.$this->enquote($k);
                $s2 .= ', ?';
            }
        }
        $sql = 'INSERT INTO '.$this->enquote($this->tablename).' ( '.substr($s1, 1).' ) VALUES ( '.substr($s2, 1).' )';
        $stmt = $this->prepare($sql);
        $i=0;
        foreach ($this->rs as $k => $v) {
            if ($k!=$pkname or $v) {
                $stmt->bindValue(++$i, $this->is_scalar_or_null($v) ? $v : ( $this->COMPRESS_ARRAY ? gzdeflate(serialize($v)) : serialize($v) ));
            }
        }

        $this->execute($stmt);

        if (! $stmt->rowCount()) {
            return false;
        }

        $this->set($pkname, $dbh->lastInsertId());
        return $this;
    }

    public function retrieve($pkvalue)
    {
        $dbh = $this->getdbh();
        $sql = 'SELECT * FROM '.$this->enquote($this->tablename).' WHERE '.$this->enquote($this->pkname).'=?';
        $stmt = $this->prepare($sql);
        $stmt->bindValue(1, ( int )$pkvalue);
        $this->execute($stmt);
        $rs = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($rs) {
            foreach ($rs as $key => $val) {
                if (array_key_exists($key, $this->rs)) {
                    $this->rs[$key] = $this->is_scalar_or_null($this->rs[$key]) ? $val : unserialize($this->COMPRESS_ARRAY ? gzinflate($val) : $val);
                }
            }
        }
        return $this;
    }

    public function update()
    {
        $dbh = $this->getdbh();
        $s='';
        foreach ($this->rs as $k => $v) {
            $s .= ', '.$this->enquote($k).'=?';
        }
        $s = substr($s, 1);
        $sql = 'UPDATE '.$this->enquote($this->tablename).' SET '.$s.' WHERE '.$this->enquote($this->pkname).'=?';
        $stmt = $this->prepare($sql);
        $i=0;
        foreach ($this->rs as $k => $v) {
            $stmt->bindValue(++$i, $this->is_scalar_or_null($v) ? $v : ( $this->COMPRESS_ARRAY ? gzdeflate(serialize($v)) : serialize($v) ));
        }
        $stmt->bindValue(++$i, $this->rs[$this->pkname]);
        return $this->execute($stmt);
    }

    public function delete()
    {
        $dbh = $this->getdbh();
        $sql = 'DELETE FROM '.$this->enquote($this->tablename).' WHERE '.$this->enquote($this->pkname).'=?';
        $stmt = $this->prepare($sql);
        $stmt->bindValue(1, $this->rs[$this->pkname]);
        return $this->execute($stmt);
    }

    //returns true if primary key is a positive integer
    //if checkdb is set to true, this function will return true if there exists such a record in the database
    public function exists($checkdb = false)
    {
        if (( int )$this->rs[$this->pkname] < 1) {
            return false;
        }
        if (!$checkdb) {
            return true;
        }
        $dbh = $this->getdbh();
        $sql = 'SELECT 1 FROM '.$this->enquote($this->tablename).' WHERE '.$this->enquote($this->pkname)."='".$this->rs[$this->pkname]."'";
        $result = $dbh->query($sql)->fetchAll();
        return count($result);
    }

    public function merge($arr)
    {
        if (! is_array($arr)) {
            return $this;
        }
        foreach ($arr as $key => $val) {
            if (array_key_exists($key, $this->rs)) {
                $this->rs[$key] = $val;
            }
        }
        return $this;
    }

    public function retrieveOne($wherewhat, $bindings)
    {
        $dbh = $this->getdbh();
        if (is_scalar($bindings)) {
            $bindings = $bindings !== '' ? array( $bindings ) : array();
        }
        $sql = 'SELECT * FROM '.$this->enquote($this->tablename);
        if (isset($wherewhat) && isset($bindings)) {
            $sql .= ' WHERE '.$wherewhat;
        }
        $sql .= ' LIMIT 1';
        $stmt = $this->prepare($sql);
        $this->execute($stmt, $bindings);
        $rs = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$rs) {
            return false;
        }
        foreach ($rs as $key => $val) {
            if (array_key_exists($key, $this->rs)) {
                $this->rs[$key] = $this->is_scalar_or_null($this->rs[$key]) ? $val : unserialize($this->COMPRESS_ARRAY ? gzinflate($val) : $val);
            }
        }
        return $this;
    }

    public function retrieveMany($wherewhat = '', $bindings = '')
    {
        $dbh = $this->getdbh();
        if (is_scalar($bindings)) {
            $bindings = $bindings !== '' ? array( $bindings ) : array();
        }
        $sql = 'SELECT * FROM '.$this->tablename;
        if ($wherewhat) {
            $sql .= ' WHERE '.$wherewhat;
        }
        $stmt = $this->prepare($sql);
        $this->execute($stmt, $bindings);
        $arr=array();
        $class=get_class($this);
        while ($rs = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $myclass = new $class();
            foreach ($rs as $key => $val) {
                if (array_key_exists($key, $myclass->rs)) {
                    $myclass->rs[$key] = $this->is_scalar_or_null($myclass->rs[$key]) ? $val : unserialize($this->COMPRESS_ARRAY ? gzinflate($val) : $val);
                }
            }
            $arr[]=$myclass;
        }
        return $arr;
    }

    public function select($selectwhat = '*', $wherewhat = '', $bindings = '', $pdoFetch_mode = \PDO::FETCH_ASSOC)
    {
        $dbh = $this->getdbh();
        if (is_scalar($bindings)) {
            $bindings = $bindings !== '' ? array( $bindings ) : array();
        }
        $sql = 'SELECT '.$selectwhat.' FROM '.$this->tablename;
        if ($wherewhat) {
            $sql .= ' WHERE '.$wherewhat;
        }
        $stmt = $this->prepare($sql);
        $this->execute($stmt, $bindings);
        return $stmt->fetchAll($pdoFetch_mode);
    }

    public function deleteWhere($wherewhat, $bindings)
    {
        $dbh = $this->getdbh();
        if (is_scalar($bindings)) {
            $bindings = array( $bindings );
        }

        $sql = 'DELETE FROM '.$this->enquote($this->tablename);
        if (isset($wherewhat) && isset($bindings)) {
            $sql .= ' WHERE '.$wherewhat;
        }
        $stmt = $this->prepare($sql);
        return $this->execute($stmt, $bindings);
    }

    private function is_scalar_or_null($value)
    {
        return is_scalar($value) || is_null($value);
    }
}
