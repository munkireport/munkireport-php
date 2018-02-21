<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Appusage extends Migration
{
    private $tableName = 'appusage';
    private $tableNameV2 = 'appusage_orig';

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
            $table->string('event');
            $table->string('bundle_id');
            $table->string('app_version');
            $table->string('app_name');
            $table->string('app_path');
            $table->bigInteger('last_time_epoch');
            $table->string('last_time');
            $table->integer('number_times');

            $table->index('serial_number');
            $table->index('event');
            $table->index('bundle_id');
            $table->index('app_version');
            $table->index('app_name');
            $table->index('app_path');
            $table->index('last_time_epoch');
            $table->index('last_time');
            $table->index('number_times');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO
                $this->tableName
            SELECT
                id,
                serial_number,
                event,
                bundle_id,
                app_version,
                app_name,
                app_path,
                last_time_epoch,
                last_time,
                number_times
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
