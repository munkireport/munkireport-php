<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CacheInit extends Migration
{
    public function up()
    {
        Schema::create('cache', function (Blueprint $table) {
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
        Schema::dropIfExists('cache');
    }
}
