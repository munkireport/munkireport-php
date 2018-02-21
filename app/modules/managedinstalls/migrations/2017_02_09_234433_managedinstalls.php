<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Managedinstalls extends Migration
{
    private $tableName = 'managedinstalls';
    private $tableNameV2 = 'managedinstalls_orig';

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
            $table->string('name');
            $table->string('display_name');
            $table->string('version', 78)->nullable();
            $table->integer('size')->nullable();
            $table->integer('installed');
            $table->string('status');
            $table->string('type');
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                display_name,
                version,
                size,
                installed,
                status,
                type
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index('display_name');
            $table->index('name');
            $table->index(['name', 'version']);
            $table->index('serial_number');
            $table->index('status');
            $table->index('type');
            $table->index('version');
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
