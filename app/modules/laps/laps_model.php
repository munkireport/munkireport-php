<?php
use CFPropertyList\CFPropertyList;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class Laps_model extends \Model
{
    private $cryptokey;
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'laps'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['useraccount'] = '';
        $this->rs['password'] = '';
        $this->rs['dateset'] = '';
        $this->rs['dateexpires'] = '';
        $this->rs['days_till_expiration'] = '';
        $this->rs['pass_length'] = '';
        $this->rs['alpha_numeric_only'] = 1; //Boolean
        $this->rs['script_enabled'] = 1; //Boolean
        $this->rs['keychain_remove'] = 1; //Boolean
        $this->rs['remote_management'] = 1; //Boolean
        $this->rs['audit'] = '';
        
        // Check if encryption key exists
        if( ! conf('laps_encryption_key')){
            throw new \Exception("No LAPS encryption key found in config", 1);
        }
        
        // Load encryption key from config.php
        $cryptokey = Key::loadFromAsciiSafeString(conf('laps_encryption_key'));

        // Retrieve data
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    // Process audit trail
    public function process_audit($data)
    {
        // Add audit trail JSON to machine record
        $this->audit = json_encode($data);
        
        // Save the data
        $this->save();
    }
    
    // Process admin save
    public function process_admin_save($data)
    {    
        // Save new data to machine record
        if (array_key_exists('dateexpires', $data)) {
            $this->dateexpires = $data->dateexpires;
        }
        $this->days_till_expiration = $data->days_till_expiration;
        $this->pass_length = $data->pass_length;
        $this->alpha_numeric_only = $data->alpha_numeric_only;
        $this->script_enabled = $data->script_enabled;
        $this->keychain_remove = $data->keychain_remove;

        // Save the data
        $this->save();
    }

    // Process incoming plist data
    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        $plist = $parser->toArray();
        
        // Check if password key exists, only save if it does
        if (array_key_exists('password', $plist)) {
            
            // Process each item for saving into the database
            foreach (array('useraccount', 'password', 'dateset', 'dateexpires', 'days_till_expiration', 'pass_length', 'alpha_numeric_only', 'script_enabled', 'keychain_remove', 'remote_management') as $item) {
                if (isset($plist[$item])) {
                    $this->$item = $plist[$item];
                } else {
                    $this->$item = '';
                }
            }
            
            // Check if encryption key exists
            if( ! conf('laps_encryption_key')){
                throw new \Exception("No LAPS encryption key found in config", 1);
            }

            // Load encryption key from config.php
            $cryptokey = Key::loadFromAsciiSafeString(conf('laps_encryption_key'));
            
            // Encrypt password
            $this->password = Crypto::encrypt($this->password, $cryptokey);
                    
            // Save the data, because we can't lose the password
            $this->save();
        }
    }
}
