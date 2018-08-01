<?php
use CFPropertyList\CFPropertyList;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class Laps_model extends \Model
{
    private $lapscryptokey;
    
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
        $this->rs['alpha_numeric_only'] = '';
        $this->rs['script_enabled'] = '';
        $this->rs['keychain_remove'] = '';
        $this->rs['remote_management'] = '';
        
        // Check if encryption key exists
        if( ! conf('laps_encryption_key')){
            throw new \Exception("No LAPS encryption key found in config", 1);
        }
        
        // Load encryption key from config.php
        $cryptokey = Key::loadFromAsciiSafeString(conf('laps_encryption_key'));

        // Retrieve data and decrypt
        if ($serial) {
            $this->retrieve_record($serial);
            if($this->password){
                try {
                    $this->password = Crypto::decrypt($this->password, $cryptokey);
                }catch (\Exception $e) {
                    $this->password = $e->getMessage();
                }
            }
        }
        
        $this->serial = $serial;
    }

    // Process incoming plist data
    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        $plist = $parser->toArray();
        
        // Check if password key exists, only save if it does
        if (array_key_exists('password', $plist)) {
          
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
