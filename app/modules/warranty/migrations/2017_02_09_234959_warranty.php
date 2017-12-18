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
            $table->string('purchase_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('status')->nullable();
            
            $table->index('purchase_date');
            $table->index('end_date');
            $table->index('status');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('warranty');
    }
}
