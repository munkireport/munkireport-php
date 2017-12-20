<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Appusage extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('appusage', function (Blueprint $table) {
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
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('appusage');
    }
}
