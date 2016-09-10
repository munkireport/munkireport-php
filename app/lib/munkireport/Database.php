<?php

namespace munkireport;

class Database
{
    
    private $config, $dbh, $error;

    public function __construct($config)
    {
        $this->config = $config;
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
                $this->config['pdo_dsn'],
                $this->config['pdo_user'],
                $this->config['pdo_pass'],
                $this->config['pdo_opts']
            );
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return $this;
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
}
