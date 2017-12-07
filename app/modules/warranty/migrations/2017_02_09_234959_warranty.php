<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Warranty extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('warranty', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('purchase_date');
            $table->string('end_date');
            $table->string('status');

            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('warranty');
    }
}
