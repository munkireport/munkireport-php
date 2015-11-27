<?php

class Inventory_model extends Model {
    
    function __construct($serial='')
    {
		parent::__construct('id', 'inventoryitem'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = (string) $serial;
        $this->rs['name'] = '';
        $this->rs['version'] = '';
        $this->rs['bundleid'] = '';
        $this->rs['bundlename'] = '';
        $this->rs['path'] = '';

        // Schema version, increment when creating a db migration
       $this->schema_version = 2;
		
		// Add indexes
		$this->idx['serial'] = array('serial_number');
		$this->idx['name_version'] = array('name', 'version');

		// Create table if it does not exist
        $this->create_table();
    }

    /**
     * Select all entries
     *
     * @return array
     * @author 
     **/
    function select_all()
    {
        $sql = sprintf('SELECT name, version, COUNT(i.id) AS num_installs
            FROM %s i 
            LEFT JOIN reportdata r ON (r.serial_number = i.serial_number)
            %s 
            GROUP BY name, version', $this->enquote($this->tablename), get_machine_group_filter('WHERE', 'r'));
        return $this->query($sql);
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
        if(preg_match('/[_%]/', $app))
        {
            $match = 'AND i.name LIKE ?';
        }
        
        $sql = sprintf('SELECT version, COUNT(i.id) AS count
            FROM %s i 
            LEFT JOIN reportdata r ON (r.serial_number = i.serial_number)
            %s 
            %s
            GROUP BY version
            ORDER BY count DESC', 
            $this->enquote($this->tablename), 
            get_machine_group_filter('WHERE', 'r'),
            $match
        );
        return $this->query($sql, $app);

    }
    
    function process($data)
    {    
        //list of bundleids to ignore
        $bundleid_ignorelist = is_array(conf('bundleid_ignorelist')) ? conf('bundleid_ignorelist') : array();
        $regex = '/^'.implode('|', $bundleid_ignorelist).'$/';

        // List of paths to ignore
        $bundlepath_ignorelist = is_array(conf('bundlepath_ignorelist')) ? conf('bundlepath_ignorelist') : array();
        $path_regex = ':^'.implode('|', $bundlepath_ignorelist).'$:';
                    
        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        $parser = new CFPropertyList();
        $parser->parse(
            $data, CFPropertyList::FORMAT_XML);
        $inventory_list = $parser->toArray();
        if (count($inventory_list))
        {
            // clear existing inventory items
            $this->delete_where('serial_number=?', $this->serial_number);
            // insert current inventory items
            foreach ($inventory_list as $item)
            {
                if (preg_match($regex, $item['bundleid']))
                {
                    continue;
                }
                if (preg_match($path_regex, $item['path']))
                {
                    continue;
                }

                $item['bundlename'] = isset($item['CFBundleName']) ? $item['CFBundleName'] : '';
            
                $this->id = 0;
                $this->merge($item)->save();
            }
        }
    }
}