<?php
class inventory extends Controller
{

    function hash($serial=NULL) {
        $hash = 'HASH';
        if (!is_null($serial))
        {
            $inventory = new InventoryReport($serial);
            if ($inventory->exists())
            {
                $hash = $inventory->get('sha256hash');
            }
        } else {
            ErrorPage::error404();
        }
        echo $hash;
    }

    function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST')
        {
            ErrorPage::error404();
        }
    
        //list of bundleids to ignore
        $bundleid_ignorelist = array('com.apple.print.PrinterProxy');
    
        $serial = isset($_POST['serial']) ? $_POST['serial'] : FALSE;
        if ($serial)
        {
            $_POST['remote_ip'] = $_SERVER['REMOTE_ADDR'];
            $client = new Client($serial);
            $client->merge($_POST)->save();
            
            $_POST['timestamp'] = time();
            $_POST['sha256hash'] = isset($_POST['inventory_report']) ? hash('sha256', $_POST['inventory_report']) : '';
            $inventory = new InventoryReport($serial);
            $inventory->merge($_POST)->save();
            
            if(isset($_POST['inventory_report']))
            {
                require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
                $parser = new CFPropertyList();
                $parser->parse(
                    $_POST['inventory_report'], CFPropertyList::FORMAT_XML);
                $inventory_list = $parser->toArray();
                if (count($inventory_list))
                {
                    // clear existing inventory items
                    $inventoryitem = new InventoryItem();
                    $inventoryitem->delete_set($serial);
                    // insert current inventory items
                    foreach ($inventory_list as $item)
                    {
                        if (!in_array($item['bundleid'], $bundleid_ignorelist))
                        {
                            $item['bundlename'] = isset($item['CFBundleName']) ? $item['CFBundleName'] : '';
                        
                            $inventoryitem = new InventoryItem($serial);
                            $inventoryitem->merge($item)->save();
                        }
                    }
                }
            }
        }
    }

    function index() {
        $inventory = new InventoryReport();
        $order = " ORDER BY timestamp DESC";
        $all_inventories = $inventory->retrieve_many('id > 0'.$order);
        $all_machines = array();
        foreach($all_inventories as $inventory)
        {
            $machine = array();
            $machine['serial'] = $inventory->get('serial');
            $machine['last_inventory_update'] = $inventory->get('timestamp');
            $client = new Client($inventory->get('serial'));
            $machine['name'] = $client->get('name');
            $machine['console_user'] = $client->get('console_user');
            $machine['remote_ip'] = $client->get('remote_ip');
            #$machine['os_version'] = $client->get('os_version');
            #$machine['cpu_arch'] = $client->get('cpu_arch');
            $all_machines[] = (object) $machine;
        }
    
        $data['all_machines'] = $all_machines;
        $data['page'] = 'inventory';

        $obj = new View();
        $obj->view('inventory_list', $data);
    
    }


    function detail($serial) {
        $client = new Client($serial);
        $report = $client->report_plist;
        
        $machine_info = array('cpu_type' => '?', 'machine_model' => '?',
         'physical_memory' => '?', 'current_processor_speed' => '?',
         'os_vers' => '?', 'arch' => '?', 'hostname' => '?',
         'available_disk_space' => '?');
        if (isset($report['AvailableDiskSpace']))
        {
            $machine_info['available_disk_space'] =
                    $report['AvailableDiskSpace'];
        }
        if (isset($report['MachineInfo']))
        {
            $machine_info = array_merge($machine_info, $report['MachineInfo']);
            if(isset($report['MachineInfo']['SystemProfile']))
            {
                foreach($report['MachineInfo']['SystemProfile'] AS $part)
                {
                    if(isset($part['_items'][0]) &&
                       is_array($part['_items'][0]))
                    {
                        $machine_info = array_merge(
                            $machine_info, $part['_items'][0]);
                    }
                }
            }
        }
        $inventory = new InventoryReport($serial);
        $machine_info['last_inventory_date'] = $inventory->timestamp;
        $machine_info = (object) $machine_info;
        
        $data['client'] = $client;
        $data['machine_info'] = $machine_info;
        $inventoryitemobj = new InventoryItem();
        $data['inventory_items'] = $inventoryitemobj->retrieve_many(
                                        'serial=?', array($serial));
        $data['page'] = 'inventory';
    
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
                $client = new Client($item->serial);
                $instance['serial'] = $item->serial;
                $instance['hostname'] = $client->name;
                $instance['username'] = $client->console_user;
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