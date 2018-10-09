<?php
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

/**
 * Laps_controller class
 *
 * @package laps
 * @author tuxudo
 **/

class Laps_controller extends Module_controller
{
	function __construct()
	{
		$this->module_path = dirname(__FILE__);
	}

    /**
     * Default method
     *
     * @author avb
     **/
    function index()
    {
        echo "You've loaded the laps module!";
    }
    
    /**
     * Retrieve password in json format
     *
     * @author tuxudo
     **/
    public function get_password($serial_number = '')
    {
        $obj = new View();
        
        // Check if authenticated
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        // Check if authorized to view password
        if(conf('view_laps_password') && ! in_array($_SESSION['user'], conf('view_laps_password'))) {
        
            // Write to audit log
            $queryobj_controller = new Laps_controller();
            $queryobj_controller->save_audit_internal($serial_number, "show_password_unauthorized");
            
            $obj->view('json', array('msg' => 'Not authorized to view password'));
            return;
        }
        
        // Check if encryption key exists
        if( ! conf('laps_encryption_key')){
            $obj->view('json', array('msg' => 'No LAPS encryption key found in config!'));
            return;
        }
        
        // Write to audit log
        $queryobj_controller = new Laps_controller();
        $queryobj_controller->save_audit_internal($serial_number, "show_password");        

        $queryobj = new Laps_model();
        $sql = "SELECT password
                    FROM laps
                    WHERE serial_number = '$serial_number'";
            
        // Run the query and process data
        $laps_audit = $queryobj->query($sql);

        // Check if password decryption is enabled
        if (conf('laps_password_decrypt_enabled')){
            // Load encryption key from config.php
            $cryptokey = Key::loadFromAsciiSafeString(conf('laps_encryption_key'));
 
            // Try to decrypt the password
            try {
                $laps_audit[0]->password = Crypto::decrypt($laps_audit[0]->password, $cryptokey);
            } catch (\Exception $e) {
                $laps_audit[0]->password = $e->getMessage();
            }
        }
        
        // Return the object as a JSON file
        $obj->view('json', array('msg' => current(array('msg' => $laps_audit[0]))));
    }
    
    /**
     * Retrieve data in json format for audit
     *
     * @author tuxudo
     **/
    public function get_audit($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $queryobj = new Laps_model();
        
        // Check if returning audit data for all serials and if user is an admin
        if ( $serial_number == '' && sess_get('role') == 'admin'){
            $sql = "SELECT serial_number, audit
                    FROM laps 
                    WHERE audit <> '' AND audit IS NOT NULL";            
            
            // Run the query and process data
            $laps_audit = $queryobj->query($sql);
            
            $out = [];
            // Build out array for all machine audits
            foreach($laps_audit as $machine_audit){
                $machine_array['serial_number'] = $machine_audit->serial_number;
                $machine_array['audit'] = $machine_audit->audit;
                array_push($out, $machine_array);            
            }

            // Return the object as a JSON file
            $obj->view('json', array('msg' => current(array('msg' => $out))));
            return;
        
        // If not an admin with no serial number
        } else if ( $serial_number == '' && sess_get('role') !== 'admin'){
            // Return the object as a JSON file
            $obj->view('json', array('msg' => current(array('msg' => "Unauthorized: Only admins can see all audits"))));
            return;
        
        // Else return audit data for one serial
        } else {
            // Else pull for only one serial
            $sql = "SELECT audit
                    FROM laps 
                    WHERE serial_number = '$serial_number'";
            
            // Run the query and process data
            $laps_audit = $queryobj->query($sql);

            // Return the object as a JSON file
            $obj->view('json', array('msg' => current(array('msg' => $laps_audit[0]))));
            return;
        }
    }

    /**
     * Retrieve client tab data in json format for client tab
     *
     * @author tuxudo
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT useraccount, dateset, dateexpires, days_till_expiration, pass_length,
                    alpha_numeric_only, script_enabled, keychain_remove, remote_management
                    FROM laps 
                    WHERE serial_number = '$serial_number'";
            
        $queryobj = new Laps_model();
        $laps_tab = $queryobj->query($sql);
            
        // Check if serial returns any data
        if (empty($laps_tab)) {
            $obj->view('json', array('msg' => "Serial not found or no macOSLAPS data"));
            return;
        }
        
        // Check if authorized to view password
        if(conf('view_laps_password') && in_array($_SESSION['user'], conf('view_laps_password'))) {
            $laps_tab[0]->password_view = 1;
        } else if (! conf('view_laps_password')) {
            $laps_tab[0]->password_view = 1;
        } else {
            $laps_tab[0]->password_view = 0;
        }

        $obj->view('json', array('msg' => $laps_tab[0]));
    }
    
     /**
     * Retrieve client tab data in json format for admin page
     *
     * @author tuxudo
     **/
    public function get_data_admin($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
                       
        // Write to audit log
        $queryobj_controller = new Laps_controller();
        $queryobj_controller->save_audit_internal($serial_number, "admin_view");
        
        $sql = "SELECT useraccount, dateset, dateexpires, days_till_expiration, pass_length,
                    alpha_numeric_only, script_enabled, keychain_remove, remote_management
                    FROM laps 
                    WHERE serial_number = '$serial_number'";
            
        $queryobj = new Laps_model();
        $laps_tab = $queryobj->query($sql);
            
        // Check if serial returns any data
        if (empty($laps_tab)) {
            $obj->view('json', array('msg' => "Serial not found or no macOSLAPS data"));
            return;
        }
        
        // Check if authorized to view password
        if(conf('view_laps_password') && in_array($_SESSION['user'], conf('view_laps_password'))) {
            $laps_tab[0]->password_view = 1;
        } else if (! conf('view_laps_password')) {
            $laps_tab[0]->password_view = 1;
        } else {
            $laps_tab[0]->password_view = 0;
        }

        $obj->view('json', array('msg' => $laps_tab[0]));
    }

    /**
     * Processes and saves admin info
     *
     * @author tuxudo
     **/
    public function save_admin_info($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
             
        // Get the incoming JSON data
        $admin_in = json_decode(stripslashes(file_get_contents("php://input")));

        // Check if the password will expire, audit accordingly
        if (property_exists($admin_in, 'dateexpires')) {
            // Write to audit log
            $queryobj_controller = new Laps_controller();
            $queryobj_controller->save_audit_internal($serial_number, "password_reset");
        } else {
            // Write to audit log
            $queryobj_controller = new Laps_controller();
            $queryobj_controller->save_audit_internal($serial_number, "remote_management");
        }
        
        // Make new model and process data
        $queryobj = new Laps_model($serial_number);
        $queryobj->process_admin_save($admin_in);
        
        // Return result that save completed
        $obj->view('json', array('status' => 'Admin info saved'));
    }
    
     /**
     * Processes and saves audit trail
     *
     * @author tuxudo
     **/
    private function save_audit_internal($serial_number = '', $action)
    {
        $queryobj_get = new Laps_model();
        $sql = "SELECT audit
                FROM laps 
                WHERE serial_number = '$serial_number'";

        // Run the query to get audit data
        $laps_audit = $queryobj_get->query($sql);

        // Get variables
        $timestamp = time();
        $username = $_SESSION['user']??"Unknown";
        $remote_ip = getRemoteAddress();
        $user_agent = $_SERVER['HTTP_USER_AGENT']??"API";
        
        // Check if previous audits exist
        if (count($laps_audit) == 0){
            // If it doesn't, make new audit
            $audit_out=array("timestamp"=>$timestamp,"username"=>$username,"remote_ip"=>$remote_ip,"user_agent"=>$user_agent,"action"=>$action);
        } else {
            // Else, add new audit to existing
            $audit_out = json_decode($laps_audit[0]->audit);
            $audit_new = array("timestamp"=>$timestamp,"username"=>$username,"remote_ip"=>$remote_ip,"user_agent"=>$user_agent,"action"=>$action);
            $audit_out[count($audit_out)] = $audit_new;
        }
        
        // Make new model and process data
        $queryobj_out = new Laps_model($serial_number);
        $queryobj_out->process_audit($audit_out);
    }
    
    /**
     * Generates an encryption key
     *
     * @author tuxudo
     **/ 
    public function generate_laps_key()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        // Gnerate and return new key
        $key = Key::createNewRandomKey();
        $key_ascii = $key->saveToAsciiSafeString();
        print_r(json_encode(array('key' => $key_ascii)));
    }
    
    /**
     * Returns password expiration and date set in JSON format
     * Largely stolen from report.php :P
     *
     * @author tuxudo
     **/ 
    public function checkin()
    {
        $obj = new View();

        // Verify passphrase
        if (isset($_POST['passphrase'])) {
            $this->group = passphrase_to_group($_POST['passphrase']);
        }

        if ($auth_list = conf('client_passphrases')) {
            if (! is_array($auth_list)) {
                $obj->view('json', array('msg' => "conf['client_passphrases'] should be an array"));
                return;
            }

            if (! isset($_POST['passphrase'])) {
                $obj->view('json', array('msg' => "passphrase is required but missing"));
                return;
            }

            if (! in_array($_POST['passphrase'], $auth_list)) {
                $obj->view('json', array('msg' => 'passphrase "'.$_POST['passphrase'].'" not accepted'));
                return;
            }
        }
        
        // Check if we have a serial and data
        if (! isset($_POST['serial'])) {
            $obj->view('json', array('msg' => "Serial is missing"));
            return;
        }

        if (! trim($_POST['serial'])) {
            $obj->view('json', array('msg' => "Serial is empty"));
            return;
        }
        
        // Get data
        $serial_number = $_POST['serial'];
        $sql = "SELECT dateexpires, dateset, script_enabled, days_till_expiration, alpha_numeric_only, keychain_remove, pass_length
                    FROM laps
                    WHERE serial_number = '$serial_number'";
        
        // Return data to client
        $queryobj = new Laps_model();
        $laps_return = $queryobj->query($sql);
	// Check if data exists for client in database
        if (!isset($laps_return[0])){
            print_r('{"days_till_expiration":"-1","dateexpires": "1", "dateset": "1"}');
        } else {
            $obj->view('json', array('msg' => $laps_return[0]));
        }        
    }
} // END class Laps_controller
