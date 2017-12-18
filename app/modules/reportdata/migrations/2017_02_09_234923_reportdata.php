<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Reportdata extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('reportdata', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('console_user')->nullable();
            $table->string('long_username')->nullable();
            $table->string('remote_ip');
            $table->integer('uptime')->nullable()->default(0);
            $table->integer('machine_group')->nullable()->default(0);
            $table->bigInteger('reg_timestamp')->default(0);
            $table->bigInteger('timestamp')->default(0);


            $table->index(['console_user']);
            $table->index(['long_username']);
            $table->index(['remote_ip']);
            $table->index(['machine_group']);
            $table->index(['reg_timestamp']);
            $table->index(['timestamp']);

        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('reportdata');
    }
}
