<?php
class Inventory_controller extends Module_controller
{
    // Require authentication
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__) .'/';
        $this->view_path = $this->module_path . 'views/';
    }

    public function index()
    {
        
        echo "You've loaded the inventory module!";
    }
    
    /**
    * Get versions and count from an application
    *
    * @param string $app Appname
    **/
    public function appVersions($app = '')
    {
        // Protect this handler
        if (! $this->authorized()) {
            redirect('auth/login');
        }
        $app = rawurldecode($app);
        $inventory_item_obj = new Inventory_model();
        $obj = new View();
        $obj->view('json', array('msg' => $inventory_item_obj->appVersions($app)));
    }

    // Todo: move expensive data objects to view
    public function items($name = '', $version = '')
    {
        // Protect this handler
        if (! $this->authorized()) {
            redirect('auth/login');
        }

        $data['inventory_items'] = array();
        $data['name'] = 'No item';

        if ($name) {
            $name = rawurldecode($name);
            $inventory_item_obj = new Inventory_model();
            $data['name'] = $name;
            if ($version) {
                $version = rawurldecode($version);
                $items = $inventory_item_obj->retrieveMany(
                    'name = ? AND version = ?',
                    array($name, $version)
                );
            } else {
                $items = $inventory_item_obj->retrieveMany(
                    'name = ?',
                    array($name)
                );
            }
            
            foreach ($items as $item) {
                $machine = new Machine_model($item->serial_number);
                // Check if authorized for this serial
                if (! $machine->id) {
                    continue;
                }
                $reportdata = new Reportdata_model($item->serial_number);
                $instance['serial_number'] = $item->serial_number;
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
