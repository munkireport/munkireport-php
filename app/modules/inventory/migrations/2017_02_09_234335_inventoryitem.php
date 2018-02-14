<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Inventoryitem extends Migration
{
    private $tableName = 'inventoryitem';
    private $tableNameV2 = 'inventoryitem_orig';

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
            $table->string('version', 78); // 78 is the max length in MySQL 5.0 due to index length
            $table->string('bundleid');
            $table->string('bundlename');
            $table->text('path');
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                version,
                bundleid,
                bundlename,
                path
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index(['name', 'version']);
            $table->index('serial_number');
            $table->index('bundleid');
            $table->index('bundlename');
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
