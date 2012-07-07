<?php
class inventory extends Controller
{

    function index() {
        
        $data['page'] = 'inventory';

        $obj = new View();
        $obj->view('inventory_list', $data);
    
    }


    function detail($serial) {

        $inventoryitemobj = new InventoryItem();
        $data['inventory_items'] = $inventoryitemobj->retrieve_many(
                                        'serial=?', array($serial));
        $data['page'] = 'inventory';
    	$data['serial'] = $serial;

        $obj = new View();
        $obj->view('inventory_detail', $data);
    }

    function items($name='', $version='') {
        if ($name)
        {
            $name = rawurldecode($name);
            $inventory_item_obj = new InventoryItem();
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
            $data['inventory_items'] = array();
            foreach ($items as $item)
            {
                $machine = new Machine($item->serial);
				$reportdata = new Reportdata($item->serial);
                $instance['serial'] = $item->serial;
                $instance['hostname'] = $machine->computer_name;
                $instance['username'] = $reportdata->console_user;
                $instance['version'] = $item->version;
                $instance['bundleid'] = $item->bundleid;
                $instance['bundlename'] = $item->bundlename;
                $instance['path'] = $item->path;
                $data['inventory_items'][] = $instance;
            }
            $obj = new View();
            $obj->view('inventoryitem_detail', $data);
        } else {
            $inventory_item_obj = new InventoryItem();
            $items = $inventory_item_obj->select(
                'DISTINCT serial, name, version');
            $inventory = array();
            foreach($items as $item)
            {
                if(!isset($inventory[$item['name']][$item['version']]))
                {
                    $inventory[$item['name']][$item['version']] = 1;
                } else {
                    $inventory[$item['name']][$item['version']]++;
                }
            }
            $data['inventory'] = $inventory;
            $data['page'] = 'inventory_items';
            $obj = new View();
            $obj->view('inventory_items', $data);
        }
    }

}