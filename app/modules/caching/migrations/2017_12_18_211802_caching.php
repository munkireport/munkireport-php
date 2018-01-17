<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Caching extends Migration
{
    private $tableName = 'caching';
    private $tableNameV2 = 'caching_orig';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }


        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();

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

            $table->index('serial_number');
            $table->index('collectiondate');
            $table->index('collectiondateepoch');
            $table->index('bytesfromcachetoclients');
            $table->index('bytesfrompeerstoclients');
            $table->index('bytesfromorigintoclients');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                collectiondate,
                expirationdate,
                collectiondateepoch,
                requestsfrompeers,
                requestsfromclients,
                bytespurgedyoungerthan1day,
                bytespurgedyoungerthan7days,
                bytespurgedyoungerthan30days,
                bytespurgedtotal,
                bytesfrompeerstoclients,
                bytesfromorigintopeers,
                bytesfromorigintoclients,
                bytesfromcachetopeers,
                bytesfromcachetoclients,
                bytesdropped,
                repliesfrompeerstoclients,
                repliesfromorigintopeers,
                repliesfromorigintoclients,
                repliesfromcachetopeers,
                repliesfromcachetoclients,
                bytesimportedbyxpc,
                bytesimportedbyhttp,
                importsbyxpc,
                importsbyhttp
            FROM
                $this->tableNameV2");
        }
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
