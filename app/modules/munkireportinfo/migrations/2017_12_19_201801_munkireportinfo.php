<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Munkireportinfo extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('munkireportinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->integer('version');
            $table->string('baseurl');
            $table->string('passphrase');
            $table->text('reportitems');
        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('munkireportinfo');
    }
}
