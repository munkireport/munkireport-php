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
            $table->string('serial_number')->nullable();
            $table->string('event')->nullable();
            $table->string('bundle_id')->nullable();
            $table->string('app_version')->nullable();
            $table->string('app_name')->nullable();
            $table->string('app_path')->nullable();
            $table->integer('last_time_epoch')->nullable();
            $table->string('last_time')->nullable();
            $table->integer('number_times')->nullable();

            $table->index('app_name');
            $table->index('app_path');
            $table->index('app_version');
            $table->index('bundle_id');
            $table->index('event');
            $table->index('last_time');
            $table->index('last_time_epoch');
            $table->index('number_times');
            // $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('appusage');
    }
}