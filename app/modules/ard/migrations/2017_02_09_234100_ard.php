<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Ard extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('ard', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('Text1')->nullable();
            $table->string('Text2')->nullable();
            $table->string('Text3')->nullable();
            $table->string('Text4')->nullable();

            $table->index('Text1', 'ard_Text1');
            $table->index('Text2', 'ard_Text2');
            $table->index('Text3', 'ard_Text3');
            $table->index('Text4', 'ard_Text4');
            // $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('ard');
    }
}
