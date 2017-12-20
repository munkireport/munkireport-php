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
            $table->string('serial_number');
            $table->string('collectiondate')->nullable();
            $table->string('expirationdate')->nullable();
            $table->bigInteger('collectiondateepoch')->nullable();
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
            $table->integer('activated')->nullable();
            $table->integer('active')->nullable();
            $table->string('cachestatus')->nullable();
            $table->bigInteger('appletvsoftware')->nullable();
            $table->bigInteger('macsoftware')->nullable();
            $table->bigInteger('iclouddata')->nullable();
            $table->bigInteger('iossoftware')->nullable();
            $table->bigInteger('booksdata')->nullable();
            $table->bigInteger('itunesudata')->nullable();
            $table->bigInteger('moviesdata')->nullable();
            $table->bigInteger('musicdata')->nullable();
            $table->bigInteger('otherdata')->nullable();
            $table->bigInteger('cachefree')->nullable();
            $table->bigInteger('cachelimit')->nullable();
            $table->bigInteger('cacheused')->nullable();
            $table->bigInteger('personalcachefree')->nullable();
            $table->bigInteger('personalcachelimit')->nullable();
            $table->bigInteger('personalcacheused')->nullable();
            $table->integer('port')->nullable();
            $table->text('publicaddress')->nullable();
            $table->text('privateaddresses')->nullable();
            $table->integer('registrationstatus')->nullable();
            $table->string('registrationerror')->nullable();
            $table->string('registrationresponsecode')->nullable();
            $table->integer('restrictedmedia')->nullable();
            $table->string('serverguid')->nullable();
            $table->string('startupstatus')->nullable();
            $table->bigInteger('totalbytesdropped')->nullable();
            $table->bigInteger('totalbytesimported')->nullable();
            $table->bigInteger('totalbytesreturnedtochildren')->nullable();
            $table->bigInteger('totalbytesreturnedtoclients')->nullable();
            $table->bigInteger('totalbytesreturnedtopeers')->nullable();
            $table->bigInteger('totalbytesstoredfromorigin')->nullable();
            $table->bigInteger('totalbytesstoredfromparents')->nullable();
            $table->bigInteger('totalbytesstoredfrompeers')->nullable();
            $table->text('reachability')->nullable();

            $table->index('serial_number');
            $table->index('collectiondate');
            $table->index('collectiondateepoch');
            $table->index('bytesfromcachetoclients');
            $table->index('bytesfrompeerstoclients');
            $table->index('bytesfromorigintoclients');
            $table->index('activated');
            $table->index('active');
            $table->index('cachestatus');
            $table->index('totalbytesreturnedtoclients');
            $table->index('totalbytesreturnedtochildren');
            $table->index('totalbytesreturnedtopeers');
            $table->index('totalbytesstoredfromorigin');
            $table->index('totalbytesstoredfromparents');
            $table->index('totalbytesstoredfrompeers');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('caching');
    }
}
