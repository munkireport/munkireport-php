<?php

namespace munkireport\lib;

class Schema
{
    private $tablename, $cmd_queue = [];
    private $numericColumns = ['INTEGER', 'BIGINT', 'REAL'];

    public function __construct($tablename)
    {
        $this->tablename = $tablename;
    }
    
    // Process callback
    public static function table($tablename, $func)
    {
        $dbh = getdbh();
        $table = new Schema($tablename);
        $func($table);
        $dbh->beginTransaction();
        $table->runCmdQueue();
        $dbh->commit();
    }
        
    // Create a string column
    public function string($column_name, $length = 255)
    {
        $type = sprintf('VARCHAR(%s)', $length);
        return $this->addColumn($type, $column_name);
    }

    // Create a TEXT column
    public function text($column_name)
    {
        $type = 'TEXT';
        return $this->addColumn($type, $column_name);
    }

    // Create a MEDIUMTEXT column
    public function mediumText($column_name)
    {
        $type = 'MEDIUMTEXT';
        return $this->addColumn($type, $column_name);
    }

    // Create a LONGTEXT column
    public function longText($column_name)
    {
        $sql = 'LONGTEXT';
        return $this->addColumn($type, $column_name);
    }

    // Create an int column
    public function integer($column_name)
    {
        $type = 'INTEGER';
        return $this->addColumn($type, $column_name);
    }

    // Create an BIGINT column
    public function bigInteger($column_name)
    {
        $type = 'BIGINT';
        return $this->addColumn($type, $column_name);
    }

    // Create a float column
    public function float($column_name)
    {
        $type = 'REAL';
        return $this->addColumn($type, $column_name);
    }

    /**
     * Enquote value
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function enquote($value='')
    {
        return "'".$value."'";
    }
    
    /**
     * Check if sql is processing a numeric column
     *
     * @param string type
     * @return return boolean
     */
    private function isNumeric($type)
    {
        return in_array($type, $this->numericColumns);
    }

    /**
     * Add a column to the query list
     *
     * @param string type type of the column
     * @param string column_name name of the column
     * @return return this
     */
    private function addColumn($type, $column_name)
    {
        $sql = 'ALTER TABLE %s ADD COLUMN %s %s';
        $this->addToCmdQueue([
            'query_type' => 'add_column',
            'is_numeric' => $this->isNumeric($type),
            'sql' => sprintf($sql, $this->tablename, $column_name, $type),
        ]);
        return $this;
    }

    // Create an index
    public function index($idx_data, $name = '')
    {
        if( ! is_array($idx_data)){
            $idx_data = array($idx_data);
        }
        $sql = sprintf("CREATE INDEX %s ON %s (%s)", 
                    $this->createIndexName($idx_data),
                    $this->tablename,
                    join(',', $idx_data));
        $this->addToCmdQueue(['query_type' => 'add_index', 'sql' => $sql]);
        
        return $this;
    }
    
    private function createIndexName($idx_data)
    {
        return $this->tablename . '_' . join('_', $idx_data);
    }
    
    private function addToCmdQueue($queue_item)
    {
        $this->cmd_queue[] = $queue_item;
        return $this;
    }
    
    
    /**
     * Exec statement with error handling
     *
     * @author AvB
     **/
    private function runCmdQueue()
    {
        // get global dbh
        $dbh = getdbh();
        
        foreach($this->cmd_queue as $queue_item)
        {
            if ($dbh->exec($queue_item['sql']) === false) {
                $err = $dbh->errorInfo();
                throw new \Exception('database error: '.$err[2]);
            }
        }
    }
}
