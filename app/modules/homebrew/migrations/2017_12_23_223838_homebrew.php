<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Homebrew extends Migration
{
    private $tableName = 'homebrew';
    private $tableNameV2 = 'homebrew_orig';

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
            $table->text('name');
            $table->text('full_name');
            $table->text('oldname');
            $table->text('aliases');
            $table->text('desc');
            $table->text('homepage');
            $table->text('installed_versions');
            $table->text('versions_stable');
            $table->text('linked_keg');
            $table->text('dependencies');
            $table->text('build_dependencies');
            $table->text('recommended_dependencies');
            $table->text('runtime_dependencies');
            $table->text('optional_dependencies');
            $table->text('requirements');
            $table->text('options');
            $table->text('used_options');
            $table->text('caveats');
            $table->text('conflicts_with');
            $table->boolean('built_as_bottle')->default(0);
            $table->boolean('installed_as_dependency')->default(0);
            $table->boolean('installed_on_request')->default(0);
            $table->boolean('poured_from_bottle')->default(0);
            $table->boolean('versions_bottle')->default(0);
            $table->boolean('keg_only')->default(0);
            $table->boolean('outdated')->default(0);
            $table->boolean('pinned')->default(0);
            $table->boolean('versions_devel')->default(0);
            $table->boolean('versions_head')->default(0);
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                name,
                full_name,
                oldname,
                aliases,
                `desc`,
                homepage,
                installed_versions,
                versions_stable,
                linked_keg,
                dependencies,
                build_dependencies,
                recommended_dependencies,
                runtime_dependencies,
                optional_dependencies,
                requirements,
                options,
                used_options,
                caveats,
                conflicts_with,
                built_as_bottle,
                installed_as_dependency,
                installed_on_request,
                poured_from_bottle,
                versions_bottle,
                keg_only,
                outdated,
                pinned,
                versions_devel,
                versions_head
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index('built_as_bottle');
            $table->index('installed_as_dependency');
            $table->index('installed_on_request');
            $table->index('poured_from_bottle');
            $table->index('keg_only');
            $table->index('outdated');
            $table->index('pinned');
            $table->index('versions_devel');
            $table->index('versions_bottle');
            $table->index('versions_head');
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
