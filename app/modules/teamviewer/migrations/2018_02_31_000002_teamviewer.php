<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Teamviewer extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('teamviewer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->integer('clientid')->nullable();
            $table->integer('clientic')->nullable();
            $table->boolean('always_online')->nullable();
            $table->integer('autoupdatemode')->nullable();
            $table->string('version')->nullable();
            $table->boolean('update_available')->nullable();
            $table->string('lastmacused')->nullable();
            $table->boolean('security_adminrights')->nullable();
            $table->integer('security_passwordstrength')->nullable();
            $table->string('meeting_username')->nullable();
            $table->integer('ipc_port_service')->nullable();
            $table->integer('licensetype')->nullable();
            $table->integer('midversion')->nullable();
            $table->boolean('moverestriction')->nullable();
            $table->boolean('is_not_first_run_without_connection')->nullable();
            $table->boolean('is_not_running_test_connection')->nullable();
            $table->boolean('had_a_commercial_connection')->nullable();
            $table->string('prefpath')->nullable();
            $table->string('updateversion')->nullable();

            $table->index('serial_number');
            $table->index('always_online');
            $table->index('autoupdatemode');
            $table->index('clientid');
            $table->index('clientic');
            $table->index('had_a_commercial_connection');
            $table->index('ipc_port_service');
            $table->index('lastmacused');
            $table->index('licensetype');
            $table->index('midversion');
            $table->index('moverestriction');
            $table->index('security_adminrights');
            $table->index('security_passwordstrength');
            $table->index('version');
            $table->index('update_available');
            $table->index('is_not_first_run_without_connection');
            $table->index('is_not_running_test_connection');
            $table->index('meeting_username');
            $table->index('prefpath');
            $table->index('updateversion');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('teamviewer');
    }
}
