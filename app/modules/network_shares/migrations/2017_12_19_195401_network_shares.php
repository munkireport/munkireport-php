<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class NetworkShares extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('network_shares', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('mntfromname')->nullable();
            $table->string('fstypename')->nullable();
            $table->string('fsmtnonname')->nullable();
            $table->boolean('automounted')->nullable();

            $table->index('name');
            $table->index('mntfromname');
            $table->index('fstypename');
            $table->index('fsmtnonname');
            $table->index('automounted');

        });

    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('network_shares');
    }
}
