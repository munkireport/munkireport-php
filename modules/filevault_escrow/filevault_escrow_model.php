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
        $this->rs['enableddate'] = '';
        $this->rs['enableduser'] = '';
        $this->rs['lvguuid'] = '';
        $this->rs['lvuuid'] = '';
        $this->rs['pvuuid'] = '';
        $this->rs['recoverykey'] = '';
        $this->rs['hddserial'] = '';

       if( ! conf('encryption_key')){
           throw new \Exception("No encryption key found in config", 1);
       }
       $this->cryptokey = Key::loadFromAsciiSafeString(conf('encryption_key'));

        if ($serial) {
            $this->retrieve_record($serial);
            if($this->recoverykey){
                try {
                    $this->recoverykey = Crypto::decrypt($this->recoverykey, $this->cryptokey);
                }catch (\Exception $e) {
                    $this->recoverykey = $e->getMessage();
                }
            }
        }
        
        $this->serial = $serial;
    }

    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        $plist = array_change_key_case($parser->toArray(), CASE_LOWER);

        foreach (array('enableddate', 'enableduser', 'lvguuid', 'lvuuid', 'pvuuid', 'recoverykey', 'hddserial') as $item) {
            if (isset($plist[$item])) {
                $this->$item = $plist[$item];
            } else {
                $this->$item = '';
            }
        }
        
        if( ! $this->recoverykey){
            throw new \Exception("No Recovery Key found!", 1);
        }
        
        // Encrypt recoverykey
        $this->recoverykey = Crypto::encrypt($this->recoverykey, $this->cryptokey);

        $this->save();
    }
}
