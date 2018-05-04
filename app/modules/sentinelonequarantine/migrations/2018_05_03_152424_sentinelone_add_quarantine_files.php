<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SentineloneAddQuarantineFiles extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('sentinelonequarantine', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('uuid')->nullable();
            $table->string('path')->nullable();

            $table->index('uuid');
            $table->index('path');
    });
    }
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('sentinelonequarantine');
    }
}
