<?php

namespace munkireport\lib;

class Database
{

    private $connection, $dbh, $error;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * connect
     *
     * Set up a database connection
     *
     * @return this or FALSE on error
     */
    public function connect()
    {
        try {
            $this->dbh = new \PDO(
                $this->getDSN(),
                $this->getUser(),
                $this->getPassword(),
                $this->getOptions()
            );
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return $this;
    }

    /**
     * Get dsn
     *
     *
     * @return string dsn
     */
    private function getDSN()
    {
        extract($this->connection, EXTR_SKIP);
        
        switch ($driver) {
            case 'sqlite':
                return "sqlite:{$database}";
            case 'mysql':
                return isset($port)
                    ? "mysql:host={$host};port={$port};dbname={$database}"
                    : "mysql:host={$host};dbname={$database}";
            default:
                throw new \Exception("Unknown driver in connection", 1);
        }
    }

    /**
     * Get pdo user
     *
     * @return string user
     */
    private function getUser()
    {
      return isset($this->connection['username']) ? $this->connection['username'] : '';
    }

    /**
     * Get pdo password
     *
     * @return string password
     */
    private function getPassword()
    {
      return isset($this->connection['password']) ? $this->connection['password'] : '';
    }

    /**
     * Get pdo options
     *
     * @return array options
     */
    private function getOptions()
    {
      return isset($this->connection['options']) ? $this->connection['options'] : [];
    }

    /**
     * Get Database handle
     *
     * Get database handle and connect if necessary
     *
     * @return $dbh or false
     */
    public function getDBH()
    {
        if (! $this->dbh) {
            if (! $this->connect()) {
                return false;
            }
        }
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $this->dbh;
    }

    /**
     * is Writable
     *
     * Check if db is writable
     *
     * @return boolean true on succes, false on failure
     */
    public function isWritable()
    {
        $dbh = $this->getDBH();
        if (! $dbh) {
            return false;
        }

        try {
            $dbh->exec("CREATE TABLE mr_temp (id TEXT)");
            $dbh->exec("DROP TABLE mr_temp");
            return true;
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * Size
     *
     * Calculate database size
     *
     * @return boolean true on succes, false on failure
     */
    public function size()
    {
        if($this->connection['driver'] == 'mysql'){
          return $this->sizeMySQL();
        }

        if($this->connection['driver'] == 'sqlite'){
          return $this->sizeSQLite();
        }
    }

    /**
     * Size
     *
     * Calculate database size
     *
     * @return float size in mb
     */
    public function sizeMySQL()
    {
        $sql = sprintf(
          "SELECT SUM(ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024 ), 2)) AS size_mb
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = '%s'",
            $this->connection['database']
          );
        if($result = $this->fetchOne($sql)){
            return (float) $result['size_mb'];
        }

    }

    /**
     * Size
     *
     * Calculate database size
     *
     * @return boolean true on succes, false on failure
     */
    public function sizeSQLite()
    {
        $result = $this->fetchOne('PRAGMA PAGE_SIZE');
        $page_size = (int) $result[0];
        $result = $this->fetchOne('PRAGMA PAGE_COUNT');
        $page_count = (int) $result[0];
        return $page_size * $page_count / 1024.0/1024.0;
    }

    /**
     * Fetch one
     *
     * Retrieve last error message
     *
     * @return string error message
     */
    public function fetchOne($sql)
    {
        $dbh = $this->getDBH();
        if (! $dbh) {
            return false;
        }
        try {
            $query = $dbh->query($sql);
            return $query->fetch();
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Get error
     *
     * Retrieve last error message
     *
     * @return string error message
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get PDO driver name
     *
     * @return string driver
     **/
    public function get_driver()
    {
        return $this->getDBH()->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }
    /**
     * Get PDO Server version
     *
     * @return string serverversion
     **/
     public function get_version()
     {
       return $this->getDBH()->getAttribute(\PDO::ATTR_SERVER_VERSION);
     }

}
