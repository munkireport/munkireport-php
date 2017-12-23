<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Diskreport extends Migration
{
    private $tableName = 'diskreport';
    private $tableNameV2 = 'diskreport_v2';
    
    public function up()
    {
        $capsule = new Capsule();

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
            $table->bigInteger('TotalSize');
            $table->bigInteger('FreeSpace');
            $table->bigInteger('Percentage');
            $table->string('SMARTStatus');
            $table->string('VolumeType');
            $table->string('media_type');
            $table->string('BusProtocol');
            $table->integer('Internal');
            $table->string('MountPoint');
            $table->string('VolumeName');
            $table->integer('CoreStorageEncrypted');
            $table->bigInteger('timestamp');

            $table->index('serial_number');
            $table->index('MountPoint');
            $table->index('media_type');
            $table->index('VolumeName');
            $table->index('VolumeType');
        });

        if ($migrateData) {
            $capsule::select('INSERT INTO 
                diskreport
            SELECT
                id,
                serial_number,
                TotalSize,
                FreeSpace,
                Percentage,
                SMARTStatus,
                VolumeType,
                media_type,
                BusProtocol,
                Internal,
                MountPoint,
                VolumeName,
                CoreStorageEncrypted,
                timestamp
            FROM
                diskreport_v2');
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
