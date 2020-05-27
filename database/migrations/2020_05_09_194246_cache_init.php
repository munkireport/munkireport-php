<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CacheInit extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('cache', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module');
            $table->string('property');
            $table->mediumText('value');
            $table->bigInteger('timestamp');

            $table->index('module');
            $table->index('property');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('cache');
    }
}
