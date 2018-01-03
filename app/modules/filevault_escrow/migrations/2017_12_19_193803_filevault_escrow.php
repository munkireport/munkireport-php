<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class FilevaultEscrow extends Migration
{
    private $tableName = 'filevault_escrow';
    private $tableNameV2 = 'filevault_escrow_v2';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }


        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('EnabledDate');
            $table->string('EnabledUser');
            $table->string('LVGUUID');
            $table->string('LVUUID');
            $table->string('PVUUID');
            $table->text('RecoveryKey');
            $table->string('HddSerial');

            $table->index('EnabledDate');
            $table->index('EnabledUser');
            $table->index('LVGUUID');
            $table->index('LVUUID');
            $table->index('PVUUID');
            $table->index('HddSerial');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                EnabledDate,
                EnabledUser,
                LVGUUID,
                LVUUID,
                PVUUID,
                RecoveryKey,
                HddSerial
            FROM
                $this->tableNameV2");
        }
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
