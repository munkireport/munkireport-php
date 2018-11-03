<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CachingHighSierria extends Migration
{
    private $tableName = 'caching';

    public function up()
    {
        $capsule = new Capsule();
    
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
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
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
			$table->dropColumn('activated');
			$table->dropColumn('active');
			$table->dropColumn('cachestatus');
			$table->dropColumn('appletvsoftware');
			$table->dropColumn('macsoftware');
			$table->dropColumn('iclouddata');
			$table->dropColumn('iossoftware');
			$table->dropColumn('booksdata');
			$table->dropColumn('itunesudata');
			$table->dropColumn('moviesdata');
			$table->dropColumn('musicdata');
			$table->dropColumn('otherdata');
			$table->dropColumn('cachefree');
			$table->dropColumn('cachelimit');
			$table->dropColumn('cacheused');
			$table->dropColumn('personalcachefree');
			$table->dropColumn('personalcachelimit');
			$table->dropColumn('personalcacheused');
			$table->dropColumn('port');
			$table->dropColumn('publicaddress');
			$table->dropColumn('privateaddresses');
			$table->dropColumn('registrationstatus');
			$table->dropColumn('registrationerror');
			$table->dropColumn('registrationresponsecode');
			$table->dropColumn('restrictedmedia');
			$table->dropColumn('serverguid');
			$table->dropColumn('startupstatus');
			$table->dropColumn('totalbytesdropped');
			$table->dropColumn('totalbytesimported');
			$table->dropColumn('totalbytesreturnedtochildren');
			$table->dropColumn('totalbytesreturnedtoclients');
			$table->dropColumn('totalbytesreturnedtopeers');
			$table->dropColumn('totalbytesstoredfromorigin');
			$table->dropColumn('totalbytesstoredfromparents');
			$table->dropColumn('totalbytesstoredfrompeers');
			$table->dropColumn('reachability');
        });
    }
}
