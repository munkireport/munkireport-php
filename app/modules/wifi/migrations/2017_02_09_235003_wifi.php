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
            
            $table->string('serial_number')->unique();
            $table->integer('agrctlrssi');
            $table->integer('agrextrssi');
            $table->integer('agrctlnoise');
            $table->integer('agrextnoise');
            $table->string('state');
            $table->string('op_mode');
            $table->integer('lasttxrate');
            $table->string('lastassocstatus');
            $table->integer('maxrate');
            $table->string('x802_11_auth');
            $table->string('link_auth');
            $table->string('bssid');
            $table->string('ssid');
            $table->integer('mcs');
            $table->string('channel');

            $table->index('bssid');
            $table->index('ssid');
            $table->index('state');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
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
