<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Memory extends Migration
{
    private $tableName = 'memory';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name')->nullable();
            $table->string('dimm_size')->nullable();
            $table->string('dimm_speed')->nullable();
            $table->string('dimm_type')->nullable();
            $table->string('dimm_status')->nullable();
            $table->string('dimm_manufacturer')->nullable();
            $table->string('dimm_part_number')->nullable();
            $table->string('dimm_serial_number')->nullable();
            $table->string('dimm_ecc_errors')->nullable();
            $table->integer('global_ecc_state')->nullable();
            $table->boolean('is_memory_upgradeable')->nullable();
            
            
            $table->index('serial_number');
            $table->index('name');
            $table->index('dimm_size');
            $table->index('dimm_speed');
            $table->index('dimm_type');
            $table->index('dimm_status');
            $table->index('dimm_manufacturer');
            $table->index('dimm_part_number');
            $table->index('dimm_serial_number');
            $table->index('dimm_ecc_errors');
            $table->index('global_ecc_state');
            $table->index('is_memory_upgradeable');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
    }
}
