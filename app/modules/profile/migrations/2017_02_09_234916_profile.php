<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Profile extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('profile', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('profile_uuid');
            $table->string('profile_name');
            $table->string('profile_removal_allowed');
            $table->string('payload_name');
            $table->string('payload_display');
            $table->binary('payload_data');
            $table->bigInteger('timestamp');

            $table->index('serial_number');
            $table->index('profile_uuid');
//            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('profile');
    }
}
