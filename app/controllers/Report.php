<?php

namespace munkireport\controller;

use \Controller, \Reportdata_model, \Messages_model, \Exception;
use munkireport\lib\Unserializer, munkireport\lib\Modules;
use munkireport\models\Hash;

class Report extends Controller
{

    public $group = 0;

    /**
     * Constructor: test if authentication is needed
     * and check if the client has the proper credentials
     *
     * @author AvB
     **/
    public function __construct()
    {
        // Flag we're on report authorization
        $GLOBALS['auth'] = 'report';

        // Check for maintenance mode
        if(file_exists(APP_ROOT . 'storage/framework/down')) {
            $this->error("MunkiReport is in maintenance mode, try again later.");
        }

        if (isset($_POST['passphrase'])) {
            $this->group = passphrase_to_group($_POST['passphrase']);
        }

        if ($auth_list = conf('client_passphrases')) {
            if (! is_array($auth_list)) {
                $this->error("conf['client_passphrases'] should be an array");
            }

            if (! isset($_POST['passphrase'])) {
                $this->error("passphrase is required but missing");
            }

            if (! in_array($_POST['passphrase'], $auth_list)) {
                $this->error('passphrase is not accepted');
            }
        }

        // Validate Serialnumber
        if (! isset($_POST['serial']) || ! trim($_POST['serial'])) {
            $this->error("Serial is missing or empty");
        }

        if ($_POST['serial'] !== filter_var($_POST['serial'], FILTER_UNSAFE_RAW))
        {
            $this->error("Serial contains illegal characters");
        }
    }

    /**
     * Hash check script for clients
     *
     * Clients check in hashes using $_POST
     * This script returns a JSON array with
     * hashes that are different
     *
     * @author AvB
     **/
    public function hash_check()
    {
        // Check if we have data
        if (! isset($_POST['items'])) {
            $this->error("Items are missing");
        }

        // Get PHP's 'upload_max_filesize', used to warn about low limits and limit larger files
        $upload_max_filesize = trim(ini_get('upload_max_filesize'));
        $last = strtolower($upload_max_filesize[strlen($upload_max_filesize)-1]);
        $upload_max_filesize = preg_replace("/[^0-9.]/", "", $upload_max_filesize);
        switch($last) 
        {
            case 'g':
            $upload_max_filesize *= 1024;
            case 'm':
            $upload_max_filesize *= 1024;
            case 'k':
            $upload_max_filesize *= 1024;
        }

        // Get PHP's 'post_max_size', used to warn about low limits and chunk larger uploads
        $post_max_size = trim(ini_get('post_max_size'));
        $last = strtolower($post_max_size[strlen($post_max_size)-1]);
        $post_max_size = preg_replace("/[^0-9.]/", "", $post_max_size);
        switch($last) 
        {
            case 'g':
            $post_max_size *= 1024;
            case 'm':
            $post_max_size *= 1024;
            case 'k':
            $post_max_size *= 1024;
        }

        $itemarr = ['error' => '', 'danger' => '', 'warning' => '', 'info' => '', 'upload_max_filesize' => $upload_max_filesize, 'post_max_size' => $post_max_size];

        // Try to register client and lookup hashes in db
        try {
            // Register check and group in reportdata   
            $this->connectDB(); 
            $this->_register($_POST['serial']);

            //$req_items = unserialize($_POST['items']); //Todo: check if array
            $unserializer = new Unserializer($_POST['items']);
            $req_items = $unserializer->unserialize();

            // Reset messages for this client
            if (isset($req_items['msg'])) {
                $msg_obj = new Messages_model();
                $msg_obj->reset($_POST['serial']);
                unset($req_items['msg']);
            }

            // Get stored hashes from db
            $hashes = Hash::select('name', 'hash')
                ->where('serial_number', post('serial'))
                ->get()
                ->pluck('hash', 'name')
                ->toArray();

            // Compare sent hashes with stored hashes
            foreach ($req_items as $name => $val) {
            // All models are lowercase
                $lkey = strtolower($name);

                // Rename legacy InventoryItem to inventory
                $lkey = str_replace('inventoryitem', 'inventory', $lkey);

                // Remove _model legacy
                if (substr($lkey, -6) == '_model') {
                    $lkey = substr($lkey, 0, -6);
                }

                if (! (isset($hashes[$lkey]) && $hashes[$lkey] == $val['hash'])) {
                    $itemarr[$name] = 1;
                }
            }
        } catch (Exception $e) {
            $this->error('hash_check: '.$e->getMessage());
        }

        // Handle errors
        foreach ($GLOBALS['alerts'] as $type => $list) {
            foreach ($list as $msg) {
                $itemarr[$type] .= "$type: $msg\n";
            }
        }

        // Return list of changed hashes
        echo serialize($itemarr);
    }

    /**
     * Check in script for clients
     *
     * Clients check in client data using $_POST
     *
     * @author AvB
     **/
    public function check_in()
    {
        if (! isset($_POST['items'])) {
            $this->error("No items in POST");
        }

        try{
            $unserializer = new Unserializer($_POST['items']);
            $arr = $unserializer->unserialize();
        }
        catch (Exception $e){
            $this->error("Could not unserialize items");
        }

        if (! is_array($arr)) {
            $this->error("Could not parse items, not a proper serialized file");
        }

        $moduleMgr = new Modules;

        foreach ($arr as $name => $val) {

            alert("starting: $name");

            // All models are lowercase
            $name = strtolower($name);

            if (preg_match('/[^\da-z_]/', $name)) {
                $this->msg("Model has an illegal name: $name");
                continue;
            }

            // All models should be part of a module
            if (substr($name, -6) == '_model') {
                $module = substr($name, 0, -6);
            } else // Legacy clients
            {
                $module = $name;
                $name = $module . '_model';
            }
            
            // Try to load processor
            if ($moduleMgr->getModuleProcessorPath($module, $processor_path))
            {
                if ($this->_runProcessor($module, $processor_path, $_POST['serial'], $val['data']))
                {
                    $this->_updateHash($_POST['serial'], $module, $val['hash']);
                }
            }
            // Otherwise run model->processor()
            elseif ($moduleMgr->getModuleModelPath($module, $model_path))
            {
                if ($this->_runModel($module, $model_path, $_POST['serial'], $val['data']))
                {
                    $this->_updateHash($_POST['serial'], $module, $val['hash']);
                }
            }
            else
            {
                $this->msg("No processor found for: $module");
            }
            $this->_collectAlerts();
        }
    }

    /**
     * Report broken client
     *
     * Use this method to report on client-side
     * errors that prevent the client to
     * report properly
     *
     * @author AvB
     **/
    public function broken_client()
    {
        // Register check in reportdata
        $this->connectDB(); 
        $this->_register($_POST['serial']);

        // Clean POST data
        $data['module'] = isset($_POST['module']) ? $_POST['module'] : 'generic';
        $data['type'] = isset($_POST['type']) ? $_POST['type'] : 'danger';
        $data['msg'] = isset($_POST['msg']) ? $_POST['msg'] : 'Unknown';
        $data['timestamp'] = time();

        // Store event
        store_event($_POST['serial'], $data['module'], $data['type'], $data['msg']);

        echo "Recorded this message: ".$data['msg']."\n";
    }

    /**
     *
     * @param string message
     * @param boolean exit or not
     * @author AvB
     **/
    public function msg($msg = 'No message', $exit = false)
    {
        echo('Server '.$msg."\n");
        if ($exit) {
            exit();
        }
    }

    /**
     * Echo serialized array with error
     * and exit
     *
     * @param string message
     * @author AvB
     **/
    public function error($msg)
    {
        echo serialize(array('error' =>$msg));
        exit();
    }
    
    private function _runModel($module, $model_path, $serial_number, $data)
    {
        require_once($model_path);

        $name = $module . '_model';

        // Capitalize classname
        $classname = '\\'.ucfirst($name);

        if (! class_exists($classname, false)) {
            $this->msg("Class not found: $classname");
            return False;
        }

        try {

           // Load model
            $class = new $classname($_POST['serial']);

            if (! method_exists($class, 'process')) {
                $this->msg("No process method in: $classname");
                return False;
            }
            $this->connectDB();
            $class->process($data);
            return True;
        } catch (Exception $e) {
            $this->msg("An error occurred while processing: $classname");
            $this->msg("Error: " . $e->getMessage());
            return False;
        }
    }
    
    private function _runProcessor($module, $processor_path, $serial_number, $data)
    {
        require_once($processor_path);

        $name = $module . '_processor';

        // Capitalize classname
        $classname = '\\'.ucfirst($name);

        if (! class_exists($classname, false)) {
            $this->msg("Class not found: $classname");
            return;
        }
        try {
            // Load model
            $class = new $classname($module, $serial_number);

            if (! method_exists($class, 'run')) {
                $this->msg("No run method in: $classname");
                return;
            }
            $this->connectDB();
            $class->run($data);
            return True;
        } catch (Exception $e) {
            $this->msg("An error occurred while processing: $classname");
            $this->msg("Error: " . $e->getMessage());
            return False;
        }
    }
    
    private function _updateHash($serial_number, $module, $hashValue)
    {
        Hash::updateOrCreate(
            [
                'serial_number' => $serial_number, 
                'name' => $module,
            ],
            [
                'name' => $module, 
                'hash' => $hashValue,
                'timestamp' => time(),
            ]
        );

    }
    
    private function _collectAlerts()
    {
        // Handle alerts
        foreach ($GLOBALS['alerts'] as $type => $list) {
           foreach ($list as $msg) {
               $this->msg("$type: $msg");
           }
           // Remove alert from array
           unset($GLOBALS['alerts'][$type]);
        }
    }

    private function _register($serial_number)
    {
        $mylist = [
            'machine_group' => $this->group,
            'remote_ip' => getRemoteAddress(),
            'timestamp' => time(),
            'archive_status' => 0, // Reset status
        ];

        $model = Reportdata_model::updateOrCreate(
            ['serial_number' => $serial_number],
            $mylist
        );
    }
}
