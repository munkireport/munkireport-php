<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Wifi extends Migration
{
    private $tableName = 'wifi';
    private $tableNameV2 = 'wifi_orig';

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
            $table->integer('agrctlrssi')->nullable();
            $table->integer('agrextrssi')->nullable();
            $table->integer('agrctlnoise')->nullable();
            $table->integer('agrextnoise')->nullable();
            $table->string('state')->nullable();
            $table->string('op_mode')->nullable();
            $table->integer('lasttxrate')->nullable();
            $table->string('lastassocstatus')->nullable();
            $table->integer('maxrate')->nullable();
            $table->string('x802_11_auth')->nullable();
            $table->string('link_auth')->nullable();
            $table->string('bssid')->nullable();
            $table->string('ssid')->nullable();
            $table->integer('mcs')->nullable();
            $table->string('channel')->nullable();
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                agrctlrssi,
                agrextrssi,
                agrctlnoise,
                agrextnoise,
                state,
                op_mode,
                lasttxrate,
                lastassocstatus,
                maxrate,
                x802_11_auth,
                link_auth,
                bssid,
                ssid,
                mcs,
                channel
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->unique('serial_number');
            $table->index('bssid');
            $table->index('ssid');
            $table->index('state');
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
