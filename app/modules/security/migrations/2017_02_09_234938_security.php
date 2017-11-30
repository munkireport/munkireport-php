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

            $table->string('serial_number');
            $table->string('gatekeeper');
            $table->string('sip');
            $table->string('ssh_users');
            $table->string('ard_users');
            $table->string('firmwarepw');
            $table->string('firewall_state');
            $table->string('skel_state');

//            $table->timestamps();

            $table->index('ard_users', 'security_ard_users');
            $table->index('firewall_state', 'security_firewall_state');
            $table->index('firmwarepw', 'security_firmwarepw');
            $table->index('gatekeeper', 'security_gatekeeper');
            $table->index('serial_number', 'security_serial_number');
            $table->index('sip', 'security_sip');
            $table->index('skel_state', 'security_skel_state');
            $table->index('ssh_users', 'security_ssh_users');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('security');
    }
}
