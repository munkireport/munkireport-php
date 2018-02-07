<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class HomebrewInfo extends Migration
{
    private $tableName = 'homebrew_info';
    private $tableNameV2 = 'homebrew_info_orig';

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

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                core_tap_head,
                core_tap_origin,
                core_tap_last_commit,
                head,
                last_commit,
                origin,
                homebrew_bottle_domain,
                homebrew_cellar,
                homebrew_prefix,
                homebrew_repository,
                homebrew_version,
                homebrew_ruby,
                command_line_tools,
                cpu,
                git,
                clang,
                java,
                perl,
                python,
                ruby,
                x11,
                xcode,
                macos,
                null,
                null,
                null
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
