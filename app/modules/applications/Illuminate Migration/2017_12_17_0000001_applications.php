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
            $table->string('serial_number')->unique();
            $table->string('name')->nullable();
            $table->text('path')->nullable();
            $table->bigInteger('last_modified')->nullable();
            $table->string('obtained_from')->nullable();
            $table->string('runtime_environment')->nullable();
            $table->string('version')->nullable();
            $table->text('info')->nullable();
            $table->string('signed_by')->nullable();
            $table->boolean('has64bit')->default(0);

            $table->index('name', 'applications_name');
            $table->index('last_modified', 'applications_last_modified');
            $table->index('obtained_from', 'applications_obtained_from');
            $table->index('runtime_environment', 'applications_runtime_environment');
            $table->index('version', 'applications_version');
            $table->index('signed_by', 'applications_signed_by');
            $table->index('has64bit', 'applications_has64bit');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('applications');
    }
}
