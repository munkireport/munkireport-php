<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class UserSessions extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('user_sessions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('event')->nullable();
            $table->integer('time')->nullable();
            $table->string('user')->nullable();
            $table->string('remote_ssh')->nullable();

            $table->index('serial_number');
            $table->index('event');
            $table->index('time');
            $table->index('user');
            $table->index('remote_ssh');

        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('user_sessions');
    }
}
