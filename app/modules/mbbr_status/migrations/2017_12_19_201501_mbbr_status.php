<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MbbrStatus extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('mbbr_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('entitlement_status');
            $table->string('machine_id');
            $table->string('install_token');


            $table->index('entitlement_status');
            $table->index('machine_id');
            $table->index('install_token');
        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('mbbr_status');
    }
}
