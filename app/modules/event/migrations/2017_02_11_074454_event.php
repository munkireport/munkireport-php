<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Event extends Migration
{
    private $tableName = 'event';
    private $tableNameV2 = 'event_orig';

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
            $table->string('type');
            $table->string('module', 50);
            $table->string('msg');
            $table->string('data')->nullable();
            $table->bigInteger('timestamp');

            //$table->timestamps();

            $table->index('msg');
            $table->index('serial_number');
            $table->index(['serial_number', 'module']);
            $table->index('type');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO
                $this->tableName
            SELECT
                id,
                serial_number,
                type,
                module,
                msg,
                data,
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
