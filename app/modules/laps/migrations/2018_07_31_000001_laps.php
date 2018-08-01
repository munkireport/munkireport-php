<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Laps extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('laps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('useraccount',128)->nullable();
            $table->text('password')->nullable();
            $table->bigInteger('dateset')->nullable();
            $table->bigInteger('dateexpires')->nullable();
            $table->integer('days_till_expiration')->nullable();
            $table->integer('pass_length')->nullable();
            $table->boolean('alpha_numeric_only')->nullable();
            $table->boolean('script_enabled')->nullable();
            $table->boolean('keychain_remove')->nullable();
            $table->boolean('remote_management')->nullable();
           
            $table->index('useraccount');
            $table->index('dateset');
            $table->index('dateexpires');
            $table->index('days_till_expiration');
            $table->index('pass_length');
            $table->index('alpha_numeric_only');
            $table->index('script_enabled');
            $table->index('keychain_remove');
            $table->index('remote_management');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('laps');
    }
}
