<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Machine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('machine', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('hostname');
            $table->string('machine_model');
            $table->string('machine_desc')->nullable();
            $table->string('img_url')->nullable();
            $table->string('cpu')->nullable();
            $table->string('current_processor_speed')->nullable();
            $table->string('cpu_arch')->nullable();
            $table->integer('os_version')->nullable();
            $table->integer('physical_memory')->nullable();
            $table->uuid('platform_UUID')->nullable();
            $table->integer('number_processors')->nullable();
            $table->string('SMC_version_system')->nullable();
            $table->string('boot_rom_version')->nullable();
            $table->string('bus_speed')->nullable();
            $table->string('computer_name')->default('No name'); // Default consistent with MR2
            $table->string('l2_cache')->nullable();
            $table->string('machine_name')->nullable();
            $table->string('packages')->nullable();
            $table->string('buildversion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('machine');
    }
}
