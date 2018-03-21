<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class FilevaultEscrowEncryptPlaintextKeys extends Migration
{
    private $tableName = 'filevault_escrow';
    private $cryptokey;
    
    public function __construct()
    {
        if( ! conf('encryption_key')){
            throw new \Exception("No encryption key found in config", 1);
        }
        $this->cryptokey = Key::loadFromAsciiSafeString(conf('encryption_key'));
    }
    
    public function up()
    {
        $capsule = new Capsule();
        
        // Find unencrypted recoverykeys
        $result = $capsule::select("SELECT id, recoverykey 
          FROM $this->tableName
          WHERE recoverykey LIKE '%-%-%-%-%-%'");
        
        // Encrypt and store recoverykeys
        foreach($result as $obj){
          $obj->recoverykey = Crypto::encrypt($obj->recoverykey, $this->cryptokey);
          $capsule::update("UPDATE $this->tableName
            SET recoverykey = :recoverykey
            WHERE id = :id", (array) $obj);
        }
   }
    
    public function down()
    {
        // We don't know which entries were plaintext, so no down()
    }
}
