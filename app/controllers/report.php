<?php

namespace munkireport\controller;

use \Controller, \Reportdata_model, \Messages_model, \Hash, \Exception;
use munkireport\lib\Unserializer, munkireport\lib\Modules;

class report extends Controller
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
                $this->error('passphrase "'.$_POST['passphrase'].'" not accepted');
            }
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
        // Check if we have a serial and data
        if (! isset($_POST['serial'])) {
            $this->error("Serial is missing");
        }

        if (! trim($_POST['serial'])) {
            $this->error("Serial is empty");
        }


        if (! isset($_POST['items'])) {
            $this->error("Items are missing");
        }

        $itemarr = array('error' => '', 'info' => '');

        // Try to register client and lookup hashes in db
        try {
            // Register check and group in reportdata
            $report = new Reportdata_model($_POST['serial']);
            $report->machine_group = $this->group;
            $report->register()->save();

            //$req_items = unserialize($_POST['items']); //Todo: check if array
            include_once(APP_PATH . '/lib/munkireport/Unserializer.php');
            $unserializer = new Unserializer($_POST['items']);
            $req_items = $unserializer->unserialize();

            // Reset messages for this client
            if (isset($req_items['msg'])) {
                $msg_obj = new Messages_model();
                $msg_obj->reset($_POST['serial']);
                unset($req_items['msg']);
            }

            // Get stored hashes from db
            $hash = new Hash();
            $hashes = $hash->all($_POST['serial']);

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
            error('hash_check: '.$e->getMessage());
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

        include_once(APP_PATH . '/lib/munkireport/Unserializer.php');
        $unserializer = new Unserializer($_POST['items']);
        $arr = $unserializer->unserialize();


        if (! is_array($arr)) {
            $this->error("Could not parse items, not a proper serialized file");
        }

        include_once(APP_PATH . '/lib/munkireport/Modules.php');
        $moduleMgr = new Modules;

        foreach ($arr as $name => $val) {
        // Skip items without data
            if (! isset($val['data'])) {
                continue;
            }

            // Rename legacy InventoryItem to inventory
            $name = str_ireplace('InventoryItem', 'inventory', $name);

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

            if (! $moduleMgr->getModuleModelPath($module, $model_path))
            {
                $this->msg("Model not found: $name");
                continue;
            }

            require_once($model_path);

            // Capitalize classname
            $classname = '\\'.ucfirst($name);

            if (! class_exists($classname, false)) {
                $this->msg("Class not found: $classname");
                continue;
            }

               // Load model
            $class = new $classname($_POST['serial']);

            if (! method_exists($class, 'process')) {
                $this->msg("No process method in: $classname");
                continue;
            }

            try {
                $class->process($val['data']);

                // Store hash
                $hash = new Hash($_POST['serial'], $module);
                $hash->hash = $val['hash'];
                $hash->timestamp = time();
                $hash->save();
            } catch (Exception $e) {
                $this->msg("An error occurred while processing: $classname");
                $this->msg("Error: " . $e->getMessage());
            }

               // Handle alerts
            foreach ($GLOBALS['alerts'] as $type => $list) {
                foreach ($list as $msg) {
                    $this->msg("$type: $msg");
                }

                // Remove alert from array
                unset($GLOBALS['alerts'][$type]);
            }
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
        // At least, we need a serial number
        if (! isset($_POST['serial'])) {
            $this->msg("Serial is missing", true);
        }

        // Register check in reportdata
        $report = new Reportdata_model($_POST['serial']);
        $report->register()->save();

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
}
