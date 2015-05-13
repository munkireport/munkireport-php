<?php

class Inventory_model extends Model {
    
    function __construct($serial='')
    {
		parent::__construct('id', 'inventoryitem'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial'] = (string) $serial;
        $this->rs['name'] = '';
        $this->rs['version'] = '';
        $this->rs['bundleid'] = '';
        $this->rs['bundlename'] = '';
        $this->rs['path'] = '';

        // Schema version, increment when creating a db migration
       $this->schema_version = 1;
		
		// Add indexes
		$this->idx['serial'] = array('serial');
		$this->idx['name_version'] = array('name', 'version');

		// Create table if it does not exist
        $this->create_table();
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
            $this->delete_where('serial=?', $this->serial);
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