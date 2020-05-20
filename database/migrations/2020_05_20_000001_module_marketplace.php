<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ModuleMarketplace extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module');
            $table->string('version')->nullable();
            $table->string('url')->nullable();
            $table->string('maintainer')->nullable();
            $table->bigInteger('date_updated')->nullable();
            $table->boolean('core')->nullable();
            $table->boolean('packagist')->nullable();

            $table->index('module');
            $table->index('version');
            $table->index('url');
            $table->index('maintainer');
            $table->index('core');
            $table->index('packagist');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('modules');
    }
}
