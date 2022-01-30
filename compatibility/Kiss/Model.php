<?php

namespace Compatibility\Kiss;
use Compatibility\Kiss\Core\Model as KISS_Model;

//===============================================================
// Model/ORM
//===============================================================

/**
 * @deprecated use Eloquent ORM
 * @property string $serial_number this property not guaranteed but in practice it will succeed every time
 */
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
            case 'mysql':
                return "TRIM('$remove' FROM $string)";
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
        return $this->getdbh()->getAttribute(\PDO::ATTR_DRIVER_NAME);
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
        while ($rs = $stmt->fetch(\PDO::FETCH_OBJ)) {
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
            throw new \Exception('database error: '.$err[2]);
        }
    }

    /**
     * Retrieve one considering machine_group membership
     * use this instead of retrieveOne
     *
     * @return Model|false
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
     * @return bool
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
     * @return array
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
     * @param string $wherewhat where
     * @param mixed $bindings statement parameters
     * @return int
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
        if ($rs = $stmt->fetch(\PDO::FETCH_OBJ)) {
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
