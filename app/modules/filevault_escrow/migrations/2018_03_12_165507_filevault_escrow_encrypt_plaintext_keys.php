<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class FilevaultEscrowEncryptPlaintextKeys extends Migration
{
    private $tableName = 'filevault_escrow';
    private $unencryptedEntries;
    private $cryptokey;
    
    public function __construct()
    {
        // First check if we have unencrypted unencrypted entries
        $this->unencryptedEntries = $this->getUnencryptedEntries();

        if( $this->unencryptedEntries && ! conf('encryption_key')){
            throw new \Exception("No encryption key found in config - cannot encrypt", 1);
        }
    }
    
    public function up()
    {
        // Nothing to encrypt, migration complete
        if( ! $this->unencryptedEntries){
            return;
        }
      
        $this->cryptokey = Key::loadFromAsciiSafeString(conf('encryption_key'));
        $capsule = new Capsule();

        // Encrypt and store recoverykeys
        foreach($this->unencryptedEntries as $obj){
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
    
    private function getUnencryptedEntries()
    {
        $capsule = new Capsule();
        if ( !$capsule->schema()->hasTable('filevault_escrow') ) {
                $capsule->schema()->create($this->tableName, function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('serial_number');
                        $table->string('enableddate');
                        $table->string('enableduser');
                        $table->string('lvguuid');
                        $table->string('lvuuid');
                        $table->string('pvuuid');
                        $table->text('recoverykey');
                        $table->string('hddserial');
                });

                $capsule->schema()->table($this->tableName, function (Blueprint $table) {
                        $table->unique('serial_number');
                        $table->index('enableddate');
                        $table->index('enableduser');
                        $table->index('lvguuid');
                        $table->index('lvuuid');
                        $table->index('pvuuid');
                        $table->index('hddserial');
                });

                return false;
        }
        return $capsule::select("SELECT id, recoverykey
          FROM $this->tableName
          WHERE recoverykey LIKE '%-%-%-%-%-%'");
    }
}
