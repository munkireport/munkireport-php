<?php

class Migration_caching_add_hs_columns extends \Model
{
    protected $new_columns = array(
        'activated' => 'INT(11)',
        'active' => 'INT(11)',
        'cachestatus' => 'VARCHAR(255)',
        'appletvsoftware' => 'BIGINT',
        'macsoftware' => 'BIGINT',
        'iclouddata' => 'BIGINT',
        'iossoftware' => 'BIGINT',
        'booksdata' => 'BIGINT',
        'itunesudata' => 'BIGINT',
        'moviesdata' => 'BIGINT',
        'musicdata' => 'BIGINT',
        'otherdata' => 'BIGINT',
        'cachefree' => 'BIGINT',
        'cachelimit' => 'BIGINT',
        'cacheused' => 'BIGINT',
        'personalcachefree' => 'BIGINT',
        'personalcachelimit' => 'BIGINT',
        'personalcacheused' => 'BIGINT',
        'port' => 'INT(11)',
        'publicaddress' => 'TEXT',
        'privateaddresses' => 'TEXT',
        'registrationstatus' => 'INT(11)',
        'registrationerror' => 'VARCHAR(255)',
        'registrationresponsecode' => 'VARCHAR(255)',
        'restrictedmedia' => 'INT(11)',
        'serverguid' => 'VARCHAR(255)',
        'startupstatus' => 'VARCHAR(255)',
        'totalbytesdropped' => 'BIGINT',
        'totalbytesimported' => 'BIGINT',
        'totalbytesreturnedtochildren' => 'BIGINT',
        'totalbytesreturnedtoclients' => 'BIGINT',
        'totalbytesreturnedtopeers' => 'BIGINT',
        'totalbytesstoredfromorigin' => 'BIGINT',
        'totalbytesstoredfromparents' => 'BIGINT',
        'totalbytesstoredfrompeers' => 'BIGINT',
        'reachability' => 'TEXT',
    );
    
    private $new_indexes = array(
        'activated',
        'active',
        'cachestatus',
        'totalbytesreturnedtoclients',
        'totalbytesreturnedtochildren',
        'totalbytesreturnedtopeers',
        'totalbytesstoredfromorigin',
        'totalbytesstoredfromparents',
        'totalbytesstoredfrompeers',
    );

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'caching';
    }

    public function up()
    {
        // Get database handle
        $dbh = $this->getdbh();

        // Wrap in transaction
        $dbh->beginTransaction();

        // Adding a column is simple...
        foreach ($this->new_columns as $column => $type) {
            $sql = "ALTER TABLE caching ADD COLUMN $column $type";
            $this->exec($sql);
        }
        
        // Add new indexes
        foreach ($this->new_indexes as $index) {
            $sql = "CREATE INDEX caching_$index ON `caching` ($index)";
            $this->exec($sql);
        }

        $dbh->commit();
    }

    public function down()
    {
        // Can't go back. Let's order a pizza instead
    }
}
