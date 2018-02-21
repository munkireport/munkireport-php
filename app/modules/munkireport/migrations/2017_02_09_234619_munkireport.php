<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Munkireport extends Migration
{
    private $tableName = 'munkireport';
    private $tableNameV2 = 'munkireport_orig';

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
            $table->string('runtype')->nullable();
            $table->string('version')->nullable();
            $table->integer('errors')->nullable();
            $table->integer('warnings')->nullable();
            $table->string('manifestname')->nullable();
            $table->longText('error_json')->nullable();
            $table->longText('warning_json')->nullable();
            $table->string('starttime')->nullable();
            $table->string('endtime')->nullable();
            $table->string('timestamp')->nullable();

            $table->index('errors');
            $table->index('manifestname');
            $table->index('runtype');
            $table->index('timestamp');
            $table->index('version');
            $table->index('warnings');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                runtype,
                version,
                errors,
                warnings,
                manifestname,
                error_json,
                warning_json,
                starttime,
                endtime,
                timestamp
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
