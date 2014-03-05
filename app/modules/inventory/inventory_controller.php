<?php
class Inventory_controller extends Module_controller
{
    // Require authentication
    function __construct()
    {
        if( ! $this->authorized())
        {
            redirect('auth/login');
        }
    } 

    function index() {
        
        echo "You've loaded the inventory module!";
    
    }

    // Todo: move expensive data objects to view
    function items($name='', $version='') 
    {
        $data['inventory_items'] = array();
        $data['name'] = 'No item';

        if ($name)
        {
            $name = rawurldecode($name);
            $inventory_item_obj = new Inventory_model();
            $data['name'] = $name;
            if ($version)
            {
                $version = rawurldecode($version);
                $items = $inventory_item_obj->retrieve_many(
                    'name = ? AND version = ?', array($name, $version));
            } else {
                $items = $inventory_item_obj->retrieve_many(
                    'name = ?', array($name));
            }
            
            foreach ($items as $item)
            {
                $machine = new Machine_model($item->serial);
				$reportdata = new Reportdata_model($item->serial);
                $instance['serial'] = $item->serial;
                $instance['hostname'] = $machine->computer_name;
                $instance['username'] = $reportdata->console_user;
                $instance['version'] = $item->version;
                $instance['bundleid'] = $item->bundleid;
                $instance['bundlename'] = $item->bundlename;
                $instance['path'] = $item->path;
                $data['inventory_items'][] = $instance;
            }
            
        }

        $obj = new View();
        $obj->view('inventory/inventoryitem_detail', $data);
    }

}