<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;


class Security extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('security', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->nullable();
            $table->string('gatekeeper')->nullable();
            $table->string('sip')->nullable();
            $table->string('ssh_users')->nullable();
            $table->string('ard_users')->nullable();
            $table->string('firmwarepw')->nullable();
            $table->string('firewall_state')->nullable();
            $table->string('skel_state')->nullable();

//            $table->timestamps();

            $table->index('ard_users');
            $table->index('firewall_state');
            $table->index('firmwarepw');
            $table->index('gatekeeper');
            $table->index('serial_number');
            $table->index('sip');
            $table->index('skel_state');
            $table->index('ssh_users');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('security');
    }
}
