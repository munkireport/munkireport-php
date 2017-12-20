<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Devtools extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('devtools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('cli_tools');
            $table->string('dashcode_version');
            $table->text('devtools_path');
            $table->string('devtools_version');
            $table->string('instruments_version');
            $table->string('interface_builder_version');
            $table->text('ios_sdks');
            $table->text('ios_simulator_sdks');
            $table->text('macos_sdks');
            $table->text('tvos_sdks');
            $table->text('tvos_simulator_sdks');
            $table->text('watchos_sdks');
            $table->text('watchos_simulator_sdks');
            $table->string('xcode_version');
            $table->string('xquartz');

            $table->index('cli_tools');
            $table->index('dashcode_version');
            $table->index('devtools_version');
            $table->index('instruments_version');
            $table->index('interface_builder_version');
            $table->index('xcode_version');
            $table->index('xquartz');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('devtools');
    }
}
