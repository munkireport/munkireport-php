<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Homebrew extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('homebrew', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->text('name')->nullable();
            $table->text('full_name')->nullable();
            $table->text('oldname')->nullable();
            $table->text('aliases')->nullable();
            $table->text('desc')->nullable();
            $table->text('homepage')->nullable();
            $table->text('installed_versions')->nullable();
            $table->text('linked_keg')->nullable();
            $table->text('dependencies')->nullable();
            $table->text('build_dependencies')->nullable();
            $table->text('recommended_dependencies')->nullable();
            $table->text('runtime_dependencies')->nullable();
            $table->text('optional_dependencies')->nullable();
            $table->text('requirements')->nullable();
            $table->text('options')->nullable();
            $table->text('used_options')->nullable();
            $table->text('caveats')->nullable();
            $table->text('conflicts_with')->nullable();
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

            $table->index('built_as_bottle', 'homebrew_built_as_bottle');
            $table->index('installed_as_dependency', 'homebrew_installed_as_dependency');
            $table->index('installed_on_request', 'homebrew_installed_on_request');
            $table->index('poured_from_bottle', 'homebrew_poured_from_bottle');
            $table->index('keg_only', 'homebrew_keg_only');
            $table->index('outdated', 'homebrew_outdated');
            $table->index('pinned', 'homebrew_pinned');
            $table->index('versions_devel', 'homebrew_versions_devel');
            $table->index('versions_bottle', 'homebrew_versions_bottle');
            $table->index('versions_head', 'homebrew_versions_head');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('homebrew');
    }
}
