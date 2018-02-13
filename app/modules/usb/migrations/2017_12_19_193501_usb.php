<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Usb extends Migration
{
    private $tableName = 'usb';
    private $tableNameV2 = 'usb_orig';

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

            $table->string('serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('vendor_id')->nullable();
            $table->string('device_speed')->nullable();
            $table->boolean('internal')->nullable();
            $table->boolean('media')->nullable();
            $table->integer('bus_power')->nullable();
            $table->integer('bus_power_used')->nullable();
            $table->integer('extra_current_used')->nullable();
            $table->string('usb_serial_number')->nullable();
            $table->text('printer_id')->nullable();
            
            
            $table->index('name');
            $table->index('type');
            $table->index('manufacturer');
            $table->index('vendor_id');
            $table->index('device_speed');
            $table->index('internal');
            $table->index('bus_power');
            $table->index('bus_power_used');
            $table->index('extra_current_used');
            $table->index('usb_serial_number');
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                type,
                manufacturer,
                vendor_id,
                device_speed,
                internal,
                media,
                bus_power,
                bus_power_used,
                extra_current_used,
                usb_serial_number,
                printer_id
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
