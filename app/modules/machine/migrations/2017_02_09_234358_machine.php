<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Machine extends Migration
{
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
            $table->string('platform_UUID')->nullable();
            $table->integer('number_processors')->nullable();
            $table->string('SMC_version_system')->nullable();
            $table->string('boot_rom_version')->nullable();
            $table->string('bus_speed')->nullable();
            $table->string('computer_name')->default('No name'); // Default consistent with MR2
            $table->string('l2_cache')->nullable();
            $table->string('machine_name')->nullable();
            $table->string('packages')->nullable();
            $table->string('buildversion')->nullable();
            
            $table->index(['serial_number']);
            $table->index(['hostname']);
            $table->index(['machine_model']);
            $table->index(['machine_desc']);
            $table->index(['cpu']);
            $table->index(['current_processor_speed']);
            $table->index(['cpu_arch']);
            $table->index(['os_version']);
            $table->index(['physical_memory']);
            $table->index(['platform_UUID']);
            $table->index(['number_processors']);
            $table->index(['SMC_version_system']);
            $table->index(['boot_rom_version']);
            $table->index(['bus_speed']);
            $table->index(['computer_name']);
            $table->index(['l2_cache']);
            $table->index(['machine_name']);
            $table->index(['packages']);
            $table->index(['buildversion']);

        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('machine');
    }
}
