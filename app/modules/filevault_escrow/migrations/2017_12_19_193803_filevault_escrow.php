<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class FilevaultEscrow extends Migration
{
    private $tableName = 'filevault_escrow';
    private $tableNameV2 = 'filevault_escrow_orig';

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
            $table->string('serial_number');
            $table->string('enableddate');
            $table->string('enableduser');
            $table->string('lvguuid');
            $table->string('lvuuid');
            $table->string('pvuuid');
            $table->text('recoverykey');
            $table->string('hddserial');
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
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
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->unique('serial_number');
            $table->index('enableddate');
            $table->index('enableduser');
            $table->index('lvguuid');
            $table->index('lvuuid');
            $table->index('pvuuid');
            $table->index('hddserial');
        });
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
