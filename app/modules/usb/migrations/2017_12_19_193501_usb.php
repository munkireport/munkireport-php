<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Usb extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('usb', function (Blueprint $table) {
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

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('usb');
    }
}
