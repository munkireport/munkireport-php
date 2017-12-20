<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class BusinessUnit extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('business_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unitid');
            $table->string('property');
            $table->string('value');

            $table->index('property');
            $table->index('value');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('business_unit');
    }
}
