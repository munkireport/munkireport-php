<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Applications extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('name');
            $table->text('path');
            $table->bigInteger('last_modified');
            $table->string('obtained_from');
            $table->string('runtime_environment');
            $table->string('version');
            $table->text('info');
            $table->string('signed_by');
            $table->boolean('has64bit')->default(0);

            $table->index('serial_number');
            $table->index('name');
            $table->index('last_modified');
            $table->index('obtained_from');
            $table->index('runtime_environment');
            $table->index('version');
            $table->index('signed_by');
            $table->index('has64bit');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('applications');
    }
}
