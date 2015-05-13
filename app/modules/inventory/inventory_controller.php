<?php
class Inventory_controller extends Module_controller
{
    // Require authentication
    function __construct()
    {        
        // Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
    } 

    function index() {
        
        echo "You've loaded the inventory module!";
    
    }

    // Todo: move expensive data objects to view
    function items($name='', $version='') 
    {
        // Protect this handler
        if( ! $this->authorized())
        {
            redirect('auth/login');
        }

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
        $obj->view('inventoryitem_detail', $data, $this->view_path);
    }

}