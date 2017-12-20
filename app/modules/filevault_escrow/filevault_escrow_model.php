<?php
// @author gmarnin
use CFPropertyList\CFPropertyList;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class Filevault_escrow_model extends \Model
{
    private $cryptokey;
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'filevault_escrow'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['EnabledDate'] = '';
        $this->rs['EnabledUser'] = '';
        $this->rs['LVGUUID'] = '';
        $this->rs['LVUUID'] = '';
        $this->rs['PVUUID'] = '';
        $this->rs['RecoveryKey'] = '';
        $this->rs['HddSerial'] = '';

        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        
        // Create table if it does not exist
       //$this->create_table();
       
       if( ! conf('encryption_key')){
           throw new \Exception("No encryption key found in config", 1);
       }
       $this->cryptokey = Key::loadFromAsciiSafeString(conf('encryption_key'));

        if ($serial) {
            $this->retrieve_record($serial);
            if($this->RecoveryKey){
                try {
                    $this->RecoveryKey = Crypto::decrypt($this->RecoveryKey, $this->cryptokey);
                }catch (\Exception $e) {
                    $this->RecoveryKey = $e->getMessage();
                }
            }
        }
        
        $this->serial = $serial;
    }

    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        
        $plist = $parser->toArray();

        foreach (array('EnabledDate', 'EnabledUser', 'LVGUUID', 'LVUUID', 'PVUUID', 'RecoveryKey', 'HddSerial') as $item) {
            if (isset($plist[$item])) {
                $this->$item = $plist[$item];
            } else {
                $this->$item = '';
            }
        }
        
        if( ! $this->RecoveryKey){
            throw new \Exception("No Recovery Key found!", 1);
        }
        
        // Encrypt recoverykey
        $this->RecoveryKey = Crypto::encrypt($this->RecoveryKey, $this->cryptokey);

        $this->save();
    }
}
