<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MunkiFacts extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('munki_facts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('fact_key');
            $table->text('fact_value');

            $table->index('serial_number');
            $table->index('fact_key');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('munki_facts');
    }
}
