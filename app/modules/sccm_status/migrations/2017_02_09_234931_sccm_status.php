<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SccmStatus extends Migration
{
    private $tableName = 'sccm_status';
    private $tableNameV2 = 'sccm_status_v2';

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
            $table->string('agent_status')->nullable();
            $table->string('mgmt_point')->nullable();
            $table->string('enrollment_name')->nullable();
            $table->string('enrollment_server')->nullable();
            $table->string('last_checkin')->nullable();
            $table->string('cert_exp')->nullable();

            $table->index('agent_status');
            $table->index('cert_exp');
            $table->index('enrollment_name');
            $table->index('enrollment_server');
            $table->index('last_checkin');
            $table->index('mgmt_point');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                agent_status,
                mgmt_point,
                enrollment_name,
                enrollment_server,
                last_checkin,
                cert_exp
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
