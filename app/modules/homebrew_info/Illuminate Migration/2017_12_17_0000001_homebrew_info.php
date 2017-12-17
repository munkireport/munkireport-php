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
            $table->string('homebrew_git_config_file')->nullable();
            $table->string('homebrew_noanalytics_this_run')->nullable();
            $table->string('curl')->nullable();

            $table->index('core_tap_head', 'homebrew_info_core_tap_head');
            $table->index('core_tap_origin', 'homebrew_info_core_tap_origin');
            $table->index('core_tap_last_commit', 'homebrew_info_core_tap_last_commit');
            $table->index('head', 'homebrew_info_head');
            $table->index('last_commit', 'homebrew_info_last_commit');
            $table->index('origin', 'homebrew_info_origin');
            $table->index('homebrew_bottle_domain', 'homebrew_info_homebrew_bottle_domain');
            $table->index('homebrew_cellar', 'homebrew_info_homebrew_cellar');
            $table->index('homebrew_prefix', 'homebrew_info_homebrew_prefix');
            $table->index('homebrew_repository', 'homebrew_info_homebrew_repository');
            $table->index('homebrew_version', 'homebrew_info_homebrew_version');
            $table->index('homebrew_ruby', 'homebrew_info_homebrew_ruby');
            $table->index('command_line_tools', 'homebrew_info_command_line_tools');
            $table->index('cpu', 'homebrew_info_cpu');
            $table->index('git', 'homebrew_info_git');
            $table->index('clang', 'homebrew_info_clang');
            $table->index('java', 'homebrew_info_java');
            $table->index('perl', 'homebrew_info_perl');
            $table->index('python', 'homebrew_info_python');
            $table->index('ruby', 'homebrew_info_ruby');
            $table->index('x11', 'homebrew_info_x11');
            $table->index('xcode', 'homebrew_info_xcode');
            $table->index('macos', 'homebrew_info_macos');
            $table->index('homebrew_git_config_file', 'homebrew_info_homebrew_git_config_file');
            $table->index('homebrew_noanalytics_this_run', 'homebrew_info_homebrew_noanalytics_this_run');
            $table->index('curl', 'homebrew_info_curl');

        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('homebrew_info');
    }
}
