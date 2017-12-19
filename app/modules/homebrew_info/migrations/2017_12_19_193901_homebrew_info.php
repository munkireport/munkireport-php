<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class HomebrewInfo extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('homebrew_info', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('core_tap_head')->nullable();
            $table->string('core_tap_origin')->nullable();
            $table->string('core_tap_last_commit')->nullable();
            $table->string('head')->nullable();
            $table->string('last_commit')->nullable();
            $table->string('origin')->nullable();
            $table->string('homebrew_bottle_domain')->nullable();
            $table->string('homebrew_cellar')->nullable();
            $table->string('homebrew_prefix')->nullable();
            $table->string('homebrew_repository')->nullable();
            $table->string('homebrew_version')->nullable();
            $table->string('homebrew_ruby')->nullable();
            $table->string('command_line_tools')->nullable();
            $table->string('cpu')->nullable();
            $table->string('git')->nullable();
            $table->string('clang')->nullable();
            $table->string('java')->nullable();
            $table->string('perl')->nullable();
            $table->string('python')->nullable();
            $table->string('ruby')->nullable();
            $table->string('x11')->nullable();
            $table->string('xcode')->nullable();
            $table->string('macos')->nullable();
        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('homebrew_info');
    }
}
