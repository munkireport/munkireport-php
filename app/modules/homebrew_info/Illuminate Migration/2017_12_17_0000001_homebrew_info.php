<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Homebrew_info extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('core_tap_head');
            $table->string('core_tap_origin');
            $table->string('core_tap_last_commit');
            $table->string('head');
            $table->string('last_commit');
            $table->string('origin');
            $table->string('homebrew_bottle_domain');
            $table->string('homebrew_cellar');
            $table->string('homebrew_prefix');
            $table->string('homebrew_repository');
            $table->string('homebrew_version');
            $table->string('homebrew_ruby');
            $table->string('command_line_tools');
            $table->string('cpu');
            $table->string('git');
            $table->string('clang');
            $table->string('java');
            $table->string('perl');
            $table->string('python');
            $table->string('ruby');
            $table->string('x11');
            $table->string('xcode');
            $table->string('macos');
            $table->string('homebrew_git_config_file');
            $table->string('homebrew_noanalytics_this_run');
            $table->string('curl');

            $table->index('core_tap_head');
            $table->index('core_tap_origin');
            $table->index('core_tap_last_commit');
            $table->index('head');
            $table->index('last_commit');
            $table->index('origin');
            $table->index('homebrew_bottle_domain');
            $table->index('homebrew_cellar');
            $table->index('homebrew_prefix');
            $table->index('homebrew_repository');
            $table->index('homebrew_version');
            $table->index('homebrew_ruby');
            $table->index('command_line_tools');
            $table->index('cpu');
            $table->index('git');
            $table->index('clang');
            $table->index('java');
            $table->index('perl');
            $table->index('python');
            $table->index('ruby');
            $table->index('x11');
            $table->index('xcode');
            $table->index('macos');
            $table->index('homebrew_git_config_file');
            $table->index('homebrew_noanalytics_this_run');
            $table->index('curl');

        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('homebrew_info');
    }
}
