<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Servermetrics extends Migration
{
    private $tableName = 'servermetrics';
    private $tableNameV2 = 'servermetrics_v2';

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
            $table->integer('afp_sessions'); // number of afp connections
            $table->integer('smb_sessions'); // number of smb connections
            $table->float('caching_cache_toclients'); //
            $table->float('caching_origin_toclients'); //
            $table->float('caching_peers_toclients'); //
            $table->float('cpu_user'); //
            $table->float('cpu_idle'); //
            $table->float('cpu_system'); //
            $table->float('cpu_nice'); //
            $table->float('memory_wired'); //
            $table->float('memory_active'); //
            $table->float('memory_inactive'); //
            $table->float('memory_free'); //
            $table->float('memory_pressure'); //
            $table->float('network_in'); //
            $table->float('network_out'); //
            $table->string('datetime'); // Datetime from record

            $table->index('datetime');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                afp_sessions,
                smb_sessions,
                caching_cache_toclients,
                caching_origin_toclients,
                caching_peers_toclients,
                cpu_user,
                cpu_idle,
                cpu_system,
                cpu_nice,
                memory_wired,
                memory_active,
                memory_inactive,
                memory_free,
                memory_pressure,
                network_in,
                network_out,
                datetime
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
