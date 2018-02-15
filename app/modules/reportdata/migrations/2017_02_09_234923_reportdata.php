<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Reportdata extends Migration
{
    private $tableName = 'reportdata';
    private $tableNameV2 = 'reportdata_orig';

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
            $table->string('console_user')->nullable();
            $table->string('long_username')->nullable();
            $table->string('remote_ip');
            $table->integer('uptime')->nullable()->default(0);
            $table->integer('machine_group')->nullable()->default(0);
            $table->bigInteger('reg_timestamp')->default(0);
            $table->bigInteger('timestamp')->default(0);
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                console_user,
                long_username,
                remote_ip,
                uptime,
                machine_group,
                reg_timestamp,
                timestamp
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->unique('serial_number');
            $table->index(['console_user']);
            $table->index(['long_username']);
            $table->index(['remote_ip']);
            $table->index(['machine_group']);
            $table->index(['reg_timestamp']);
            $table->index(['timestamp']);
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
