<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Sentinelone extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('sentinelone', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->integer('active_threats_present')->nullable();
            $table->string('agent_id')->nullable();
            $table->integer('agent_running')->nullable();
            $table->string('agent_version')->nullable();
            $table->integer('enforcing_security')->nullable();
            $table->string('last_seen')->nullable();
            $table->string('mgmt_url')->nullable();
            $table->integer('self_protection_enabled')->nullable();

            $table->index('serial_number');
            $table->index('active_threats_present');
            $table->index('agent_id');
            $table->index('agent_running');
            $table->index('agent_version');
            $table->index('enforcing_security');
            $table->index('last_seen');
            $table->index('mgmt_url');
            $table->index('self_protection_enabled');
    });
    }
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('sentinelone');
    }
}
