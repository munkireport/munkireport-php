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

            $table->string('serial_number')->unique();
            $table->string('gatekeeper');
            $table->string('sip');
            $table->string('ssh_users');
            $table->string('ard_users');
            $table->string('firmwarepw');
            $table->string('firewall_state');
            $table->string('skel_state');

            $table->index('gatekeeper');
            $table->index('sip');
            $table->index('ssh_users');
            $table->index('ard_users');
            $table->index('firmwarepw');
            $table->index('firewall_state');
            $table->index('skel_state');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('security');
    }
}
