<?php

namespace App\Http\Controllers\Client;


use App\Notifications\BrokenClient;
use App\Notifications\CheckIn;
use App\Notifications\GlobalNotifiable;
use App\ReportData;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Compatibility\Kiss\ConnectDbTrait;
use munkireport\lib\Modules;
use \Messages_model, \Exception;
use munkireport\models\Hash as MunkiReportHash;
use function xKerman\Restricted\unserialize;
use xKerman\Restricted\UnserializeFailedException;


class ReportController extends Controller
{
    use ConnectDbTrait;

    public $group = 0;

    /**
     * Core Processors.
     *
     * TODO: find a better place for registering these, maybe as a Service Container tagged service.
     *
     * @var string[]
     */
    public static $processors = [
        'reportdata' => \munkireport\processors\ReportDataProcessor::class,
        'machine' => \munkireport\processors\MachineProcessor::class,
    ];

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
    }

    /**
     * Some of the code originally in __construct() prevented the route:list command from working on the CLI.
     * This initialisation code has been moved here.
     *
     * NOTE: The Illuminate/Testing framework does not populate superglobals like $_GET and $_POST, so using them here
     * makes them incompatible with testing.
     */
    public function init(Request $request)
    {
        // Validate Serialnumber
        if (!$request->has('serial') || !trim($request->get('serial'))) {
            $this->error("Serial is missing or empty");
        }

        if ($request->get('serial') !== filter_var($request->get('serial'), FILTER_SANITIZE_STRING))
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
    public function hash_check(Request $request)
    {
        $this->init($request);

        // Check if we have data
        if (!$request->has('items')) {
            return response(serialize(array('error' => 'Items are missing')));
        }

        $itemarr = ['error' => '', 'danger' => '', 'warning' => '', 'info' => ''];

        // Try to register client and lookup hashes in db
        try {
            // Register check and group in reportdata
            $this->_register($request->post('serial'));

            try{
                $req_items = unserialize($request->get('items'));
            }
            catch (Exception $e){
                Log::error($e);
                return response(serialize(array('error' => 'Could not unserialize items')));
            }

            // Reset messages for this client
            if (isset($req_items['msg'])) {
                $msg_obj = new Messages_model();
                $msg_obj->reset($request->post('serial'));
                unset($req_items['msg']);
            }

            // Get stored hashes from db
            $hashes = MunkiReportHash::select('name', 'hash')
                ->where('serial_number', request('serial', ''))
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
            return response(serialize(array('error' => 'hash_check: ' . $e->getMessage())));
        }

        // Handle errors
        foreach ($GLOBALS['alerts'] as $type => $list) {
            foreach ($list as $msg) {
                $itemarr[$type] .= "$type: $msg\n";
            }
        }

        // Return list of changed hashes
        return response(serialize($itemarr));
    }

    /**
     * Check in script for clients
     *
     * Clients check in client data using $_POST
     *
     * The client will send a form encoded request with three keys:
     *
     * - serial: The serial number for the report we are submitting.
     * - passphrase: "None" or a specific passphrase if assigned to a Machine Group.
     * - items: A php serialize()'d string of all report data.
     *
     * v6.x NOTE: superglobals such as $_POST and $_GET are not compatible with the Illuminate\Testing framework
     *            and have been rewritten to use the Request type.
     *
     * @author AvB
     **/
    public function check_in(Request $request)
    {
        $this->init($request);

        if (!$request->has('items')) {
            Log::warning("No items in POST");
            return response(serialize(array('error' => 'No items in POST')));
        }

        // $global = new GlobalNotifiable();
        // Way too noisy - used for debugging notifications
        // $global->notify(new CheckIn($_POST['serial']));

        try{
            $arr = unserialize($request->get('items'));
        }
        catch (Exception $e){
            Log::error($e);
            return response(serialize(array('error' => 'Could not unserialize items')));
        }

        if (! is_array($arr)) {
            return response(serialize(array('error' => 'Could not parse items, not a proper serialized file')));
        }

        $moduleMgr = new Modules;

        foreach ($arr as $name => $val) {

            $this->_info("starting: $name");

            // All models are lowercase
            $name = strtolower($name);

            if (preg_match('/[^\da-z_]/', $name)) {
                $this->_info("Model has an illegal name: $name");
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

            if ($this->_runCoreProcessor($module, $request->post('serial'), $val['data'])) {
                $this->_updateHash($request->post('serial'), $module, $val['hash']);
            }
            // Try to load processor
            elseif ($moduleMgr->getModuleProcessorPath($module, $processor_path))
            {
                if ($this->_runProcessor($module, $processor_path, $request->post('serial'), $val['data']))
                {
                    $this->_updateHash($request->post('serial'), $module, $val['hash']);
                }
            }
            // Otherwise run model->processor()
            elseif ($moduleMgr->getModuleModelPath($module, $model_path))
            {
                if ($this->_runModel($module, $model_path, $request->post('serial'), $val['data']))
                {
                    $this->_updateHash($request->post('serial'), $module, $val['hash']);
                }
            }
            else
            {
                $this->_info("No processor found for: $module");
            }
            $this->_collectAlerts();
        }

        return response(null);
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
    public function broken_client(Request $request)
    {
        $this->init($request);

        // Register check in reportdata
        $this->_register($request->post('serial'));

        // Store event
        store_event(
            $request->post('serial'),
            $request->post('module', 'generic'),
            $request->post('type', 'danger'),
            $request->post('msg', 'Unknown'),
            '', false);

        Notification::send(User::all(), new BrokenClient(
            $request->post('msg', 'Unknown'),
            $request->post('module', 'generic'),
            $request->post('type', 'danger'),
            $request->post('serial'),
            $request->post('name'))
        );

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

    private function _info($msg)
    {
        $GLOBALS['alerts']['info'][] = $msg;
    }

    private function _warning($msg)
    {
        $GLOBALS['alerts']['warning'][] = $msg;
    }

    private function _runModel($module, $model_path, $serial_number, $data)
    {
        require_once($model_path);

        $name = $module . '_model';

        // Capitalize classname
        $classname = '\\'.ucfirst($name);

        if (! class_exists($classname, false)) {
            $this->_warning("Class not found: $classname");
            return False;
        }

        try {

            // Load model
            $class = new $classname($serial_number);

            if (! method_exists($class, 'process')) {
                $this->_warning("No process method in: $classname");
                return False;
            }
            $this->connectDB();
            $class->process($data);
            return True;
        } catch (Exception $e) {
            $this->_warning("An error occurred while processing: $classname");
            $this->_warning("Error: " . $e->getMessage());
            return False;
        }
    }

    private function _runCoreProcessor($module, $serial_number, $data): bool
    {
        if (array_key_exists($module, static::$processors)) {
            try {
                // Load model
                $classname = static::$processors[$module];
                $class = new $classname($module, $serial_number);

                if (! method_exists($class, 'run')) {
                    $this->_warning("No run method in: $classname");
                    return False;
                }
                $this->connectDB();
                $class->run($data);
                return True;
            } catch (Exception $e) {
                $this->_warning("An error occurred while processing: $classname");
                $this->_warning("Error: " . $e->getMessage());
                return False;
            }
        } else {
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
            $this->_warning("Class not found: $classname");
            return;
        }
        try {
            // Load model
            $class = new $classname($module, $serial_number);

            if (! method_exists($class, 'run')) {
                $this->_warning("No run method in: $classname");
                return;
            }
            $this->connectDB();
            $class->run($data);
            return True;
        } catch (Exception $e) {
            $this->_warning("An error occurred while processing: $classname");
            $this->_warning("Error: " . $e->getMessage());
            return False;
        }
    }

    private function _updateHash($serial_number, $module, $hashValue)
    {
        MunkiReportHash::updateOrCreate(
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
            'remote_ip' => request()->getClientIp(),
            'timestamp' => time(),
            'archive_status' => 0, // Reset status
        ];

        $model = ReportData::updateOrCreate(
            ['serial_number' => $serial_number],
            $mylist
        );
    }
}
