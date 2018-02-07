<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Gpu extends Migration
{
    private $tableName = 'gpu';
    private $tableNameV2 = 'gpu_orig';

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

            $table->string('model')->nullable();
            $table->string('vendor')->nullable();
            $table->string('vram')->nullable();
            $table->string('pcie_width')->nullable();
            $table->string('slot_name')->nullable();
            $table->string('device_id')->nullable();
            $table->string('gmux_version')->nullable();
            $table->string('efi_version')->nullable();
            $table->string('revision_id')->nullable();
            $table->string('rom_revision')->nullable();

            $table->index('serial_number');
            $table->index('model');
            $table->index('vendor');
            $table->index('vram');
            $table->index('pcie_width');
            $table->index('slot_name');
            $table->index('device_id');
            $table->index('gmux_version');
            $table->index('efi_version');
            $table->index('revision_id');
            $table->index('rom_revision');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                model,
                vendor,
                vram,
                pcie_width,
                slot_name,
                device_id,
                gmux_version,
                efi_version,
                revision_id,
                rom_revision
            FROM
                $this->tableNameV2");
        }
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
