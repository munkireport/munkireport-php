<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Machine extends Migration
{
    private $tableName = 'machine';
    private $tableNameV2 = 'machine_orig';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }

        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
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
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                hostname,
                machine_model,
                machine_desc,
                img_url,
                cpu,
                current_processor_speed,
                cpu_arch,
                os_version,
                physical_memory,
                platform_UUID,
                number_processors,
                SMC_version_system,
                boot_rom_version,
                bus_speed,
                computer_name,
                l2_cache,
                machine_name,
                packages,
                buildversion
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->unique('serial_number');
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
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
