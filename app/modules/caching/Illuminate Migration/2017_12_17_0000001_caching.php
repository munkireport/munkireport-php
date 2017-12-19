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
            $table->string('collectiondate');
            $table->string('expirationdate');
            $table->bigInteger('collectiondateepoch');
            $table->bigInteger('requestsfrompeers');
            $table->bigInteger('requestsfromclients');
            $table->bigInteger('bytespurgedyoungerthan1day');
            $table->bigInteger('bytespurgedyoungerthan7days');
            $table->bigInteger('bytespurgedyoungerthan30days');
            $table->bigInteger('bytespurgedtotal');
            $table->bigInteger('bytesfrompeerstoclients');
            $table->bigInteger('bytesfromorigintopeers');
            $table->bigInteger('bytesfromorigintoclients');
            $table->bigInteger('bytesfromcachetopeers');
            $table->bigInteger('bytesfromcachetoclients');
            $table->bigInteger('bytesdropped');
            $table->bigInteger('repliesfrompeerstoclients');
            $table->bigInteger('repliesfromorigintopeers');
            $table->bigInteger('repliesfromorigintoclients');
            $table->bigInteger('repliesfromcachetopeers');
            $table->bigInteger('repliesfromcachetoclients');
            $table->bigInteger('bytesimportedbyxpc');
            $table->bigInteger('bytesimportedbyhttp');
            $table->bigInteger('importsbyxpc');
            $table->bigInteger('importsbyhttp');
            $table->integer('activated');
            $table->integer('active');
            $table->string('cachestatus');
            $table->bigInteger('appletvsoftware');
            $table->bigInteger('macsoftware');
            $table->bigInteger('iclouddata');
            $table->bigInteger('iossoftware');
            $table->bigInteger('booksdata');
            $table->bigInteger('itunesudata');
            $table->bigInteger('moviesdata');
            $table->bigInteger('musicdata');
            $table->bigInteger('otherdata');
            $table->bigInteger('cachefree');
            $table->bigInteger('cachelimit');
            $table->bigInteger('cacheused');
            $table->bigInteger('personalcachefree');
            $table->bigInteger('personalcachelimit');
            $table->bigInteger('personalcacheused');
            $table->integer('port');
            $table->text('publicaddress');
            $table->text('privateaddresses');
            $table->integer('registrationstatus');
            $table->string('registrationerror');
            $table->string('registrationresponsecode');
            $table->integer('restrictedmedia');
            $table->string('serverguid');
            $table->string('startupstatus');
            $table->bigInteger('totalbytesdropped');
            $table->bigInteger('totalbytesimported');
            $table->bigInteger('totalbytesreturnedtochildren');
            $table->bigInteger('totalbytesreturnedtoclients');
            $table->bigInteger('totalbytesreturnedtopeers');
            $table->bigInteger('totalbytesstoredfromorigin');
            $table->bigInteger('totalbytesstoredfromparents');
            $table->bigInteger('totalbytesstoredfrompeers');
            $table->text('reachability');

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
