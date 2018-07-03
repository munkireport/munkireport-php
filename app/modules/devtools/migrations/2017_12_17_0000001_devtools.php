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
            $table->string('cli_tools',191)->nullable();
            $table->string('dashcode_version',191)->nullable();
            $table->text('devtools_path')->nullable();
            $table->string('devtools_version',191)->nullable();
            $table->string('instruments_version',191)->nullable();
            $table->string('interface_builder_version',191)->nullable();
            $table->text('ios_sdks')->nullable();
            $table->text('ios_simulator_sdks')->nullable();
            $table->text('macos_sdks')->nullable();
            $table->text('tvos_sdks')->nullable();
            $table->text('tvos_simulator_sdks')->nullable();
            $table->text('watchos_sdks')->nullable();
            $table->text('watchos_simulator_sdks')->nullable();
            $table->string('xcode_version',191)->nullable();
            $table->string('xquartz',191)->nullable();

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
