<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Diskreport extends Migration
{
    private $tableName = 'diskreport';
    private $tableNameV2 = 'diskreport_orig';
    
    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2) && !$capsule::schema()->hasTable($this->tableName)) {
            // Migration already failed before, but didnt finish
//            throw new Exception("previous failed migration exists. You will need to delete table diskreport, and rename diskreport_orig back to diskreport");
            $this->down();
        }

        if ($capsule::schema()->hasTable($this->tableName) && !$capsule::schema()->hasTable($this->tableNameV2)) {
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
            $table->string('media_type')->nullable(); // In order to support upgrade from earlier versions
            $table->string('busprotocol');
            $table->integer('internal');
            $table->string('mountpoint');
            $table->string('volumename');
            $table->integer('encrypted');
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
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
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index('serial_number');
            $table->index('mountpoint');
            $table->index('media_type');
            $table->index('volumename');
            $table->index('volumetype');
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
