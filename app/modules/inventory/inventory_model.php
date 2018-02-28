<?php

use CFPropertyList\CFPropertyList;

class Inventory_model extends \Model
{
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'inventoryitem'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = (string) $serial;
        $this->rs['name'] = '';
        $this->rs['version'] = '';
        $this->rs['bundleid'] = '';
        $this->rs['bundlename'] = '';
        $this->rs['path'] = '';
        $this->rt['path'] = 'VARCHAR(1024)';
    }

    /**
     * Select all entries
     *
     * @return array
     * @author
     **/
    public function select_all()
    {
        $sql = sprintf('SELECT name, version, COUNT(i.id) AS num_installs
            FROM %s i 
            LEFT JOIN reportdata r ON (r.serial_number = i.serial_number)
            %s 
            GROUP BY name, version', $this->enquote($this->tablename), get_machine_group_filter('WHERE', 'r'));
        return $this->query($sql);
    }
    
    // Override for retrieveMany that takes machine groups into account
    public function retrieveMany($wherewhat = '', $bindings = '')
    {
        $dbh = $this->getdbh();
        if (is_scalar($bindings)) {
            $bindings = $bindings !== '' ? array( $bindings ) : array();
        }
        $sql = 'SELECT * FROM '.$this->tablename;
        $sql .= ' JOIN reportdata USING (serial_number)';
        $sql .= get_machine_group_filter('WHERE');
        if ($wherewhat) {
            $sql .= ' AND '.$wherewhat;
        }
        $stmt = $this->prepare($sql);
        $this->execute($stmt, $bindings);
        $arr=array();
        $class=get_class($this);
        while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $myclass = new $class();
            foreach ($rs as $key => $val) {
                if (array_key_exists($key, $myclass->rs)) {
                    $myclass->rs[$key] = is_scalar($myclass->rs[$key]) ? $val : unserialize($this->COMPRESS_ARRAY ? gzinflate($val) : $val);
                }
            }
            $arr[]=$myclass;
        }
        return $arr;
    }
    
    /**
     * Get versions and count from an application
     *
     * @param string $app Appname
     **/
    public function appVersions($app = '')
    {
        // Detect wildcard character
        $match = 'AND i.name = ?';
        if (preg_match('/[_%]/', $app)) {
            $match = 'AND i.name LIKE ?';
        }
        
        $sql = sprintf(
            'SELECT version, COUNT(i.id) AS count
            FROM %s i 
            LEFT JOIN reportdata r ON (r.serial_number = i.serial_number)
            %s 
            %s
            GROUP BY version
            ORDER BY version DESC',
            $this->enquote($this->tablename),
            get_machine_group_filter('WHERE', 'r'),
            $match
        );
        return $this->query($sql, $app);
    }
    
    public function process($data)
    {
        //list of bundleids to ignore
        $bundleid_ignorelist = is_array(conf('bundleid_ignorelist')) ? conf('bundleid_ignorelist') : array();
        $regex = '/^'.implode('|', $bundleid_ignorelist).'$/';

        // List of paths to ignore
        $bundlepath_ignorelist = is_array(conf('bundlepath_ignorelist')) ? conf('bundlepath_ignorelist') : array();
        $path_regex = ':^'.implode('|', $bundlepath_ignorelist).'$:';
                    
        $parser = new CFPropertyList();
        $parser->parse(
            $data,
            CFPropertyList::FORMAT_XML
        );
        $inventory_list = $parser->toArray();
        if (count($inventory_list)) {
        // clear existing inventory items
            $this->deleteWhere('serial_number=?', $this->serial_number);
            // insert current inventory items
            foreach ($inventory_list as $item) {
                if (preg_match($regex, $item['bundleid'])) {
                    continue;
                }
                if (preg_match($path_regex, $item['path'])) {
                    continue;
                }

                $item['bundlename'] = isset($item['CFBundleName']) ? $item['CFBundleName'] : '';
            
                $this->id = 0;
                $this->merge($item)->save();
            }
        }
    }
}
