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
            $table->bigInteger('totalsize');
            $table->bigInteger('freespace');
            $table->bigInteger('percentage');
            $table->string('smartstatus');
            $table->string('volumetype');
            $table->string('media_type');
            $table->string('busprotocol');
            $table->integer('internal');
            $table->string('mountpoint');
            $table->string('volumename');
            $table->integer('encrypted');

            $table->index('serial_number');
            $table->index('mountpoint');
            $table->index('media_type');
            $table->index('volumename');
            $table->index('volumetype');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
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
                CoreStorageEncrypted
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
