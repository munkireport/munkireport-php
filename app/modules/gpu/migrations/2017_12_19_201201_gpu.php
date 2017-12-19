<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Gpu extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('gpu', function (Blueprint $table) {
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

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('gpu');
    }
}
