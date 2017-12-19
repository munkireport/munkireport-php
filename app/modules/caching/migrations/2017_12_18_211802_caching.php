<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Caching extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('caching', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();

            $table->string('collectiondate')->nullable();
            $table->string('expirationdate')->nullable();
            $table->integer('collectiondateepoch')->nullable();
            $table->bigInteger('requestsfrompeers')->nullable();
            $table->bigInteger('requestsfromclients')->nullable();
            $table->bigInteger('bytespurgedyoungerthan1day')->nullable();
            $table->bigInteger('bytespurgedyoungerthan7days')->nullable();
            $table->bigInteger('bytespurgedyoungerthan30days')->nullable();
            $table->bigInteger('bytespurgedtotal')->nullable();
            $table->bigInteger('bytesfrompeerstoclients')->nullable();
            $table->bigInteger('bytesfromorigintopeers')->nullable();
            $table->bigInteger('bytesfromorigintoclients')->nullable();
            $table->bigInteger('bytesfromcachetopeers')->nullable();
            $table->bigInteger('bytesfromcachetoclients')->nullable();
            $table->bigInteger('bytesdropped')->nullable();
            $table->bigInteger('repliesfrompeerstoclients')->nullable();
            $table->bigInteger('repliesfromorigintopeers')->nullable();
            $table->bigInteger('repliesfromorigintoclients')->nullable();
            $table->bigInteger('repliesfromcachetopeers')->nullable();
            $table->bigInteger('repliesfromcachetoclients')->nullable();
            $table->bigInteger('bytesimportedbyxpc')->nullable();
            $table->bigInteger('bytesimportedbyhttp')->nullable();
            $table->bigInteger('importsbyxpc')->nullable();
            $table->bigInteger('importsbyhttp')->nullable();

            $table->index('serial_number');
            $table->index('collectiondate');
            $table->index('collectiondateepoch');
            $table->index('bytesfromcachetoclients');
            $table->index('bytesfrompeerstoclients');
            $table->index('bytesfromorigintoclients');
            // $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('caching');
    }
}
