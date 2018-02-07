<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Location extends Migration
{
    private $tableName = 'location';
    private $tableNameV2 = 'location_orig';

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
            $table->string('address')->nullable();
            $table->integer('altitude')->default(0);
            $table->string('currentstatus');
            $table->boolean('ls_enabled')->default(false);
            $table->string('lastlocationrun');
            $table->string('lastrun');
            $table->double('latitude')->default(0.0);
            $table->integer('latitudeaccuracy')->default(0);
            $table->double('longitude')->default(0.0);
            $table->integer('longitudeaccuracy')->default(0);
            $table->string('stalelocation')->nullable();

            $table->index('address');
            $table->index('currentstatus');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                address,
                altitude,
                currentstatus,
                ls_enabled,
                lastlocationrun,
                lastrun,
                latitude,
                latitudeaccuracy,
                longitude,
                longitudeaccuracy,
                stalelocation
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
