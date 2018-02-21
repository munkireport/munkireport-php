<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Softwareupdate extends Migration
{
    private $tableName = 'softwareupdate';
    private $tableNameV2 = 'softwareupdate_orig';

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
            $table->string('serial_number');
            $table->integer('automaticcheckenabled')->nullable();
            $table->integer('automaticdownload')->nullable();
            $table->integer('configdatainstall')->nullable();
            $table->integer('criticalupdateinstall')->nullable();
            $table->string('lastattemptsystemversion')->nullable();
            $table->string('lastbackgroundccdsuccessfuldate')->nullable();
            $table->string('lastbackgroundsuccessfuldate')->nullable();
            $table->string('lastfullsuccessfuldate')->nullable();
            $table->integer('lastrecommendedupdatesavailable')->nullable();
            $table->integer('lastresultcode')->nullable();
            $table->integer('lastsessionsuccessful')->nullable();
            $table->string('lastsuccessfuldate')->nullable();
            $table->integer('lastupdatesavailable')->nullable();
            $table->integer('skiplocalcdn')->nullable();
            $table->string('recommendedupdates')->nullable();
            $table->string('mrxprotect')->nullable();
            $table->string('catalogurl')->nullable();
            $table->string('inactiveupdates')->nullable();
        });

        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                automaticcheckenabled,
                automaticdownload,
                configdatainstall,
                criticalupdateinstall,
                lastattemptsystemversion,
                lastbackgroundccdsuccessfuldate,
                lastbackgroundsuccessfuldate,
                lastfullsuccessfuldate,
                lastrecommendedupdatesavailable,
                lastresultcode,
                lastsessionsuccessful,
                lastsuccessfuldate,
                lastupdatesavailable,
                skiplocalcdn,
                recommendedupdates,
                mrxprotect,
                catalogurl,
                inactiveupdates
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->unique('serial_number');
            $table->index('automaticcheckenabled');
            $table->index('automaticdownload');
            $table->index('configdatainstall');
            $table->index('criticalupdateinstall');
            $table->index('lastattemptsystemversion');
            $table->index('lastbackgroundccdsuccessfuldate');
            $table->index('lastbackgroundsuccessfuldate');
            $table->index('lastfullsuccessfuldate');
            $table->index('lastrecommendedupdatesavailable');
            $table->index('lastresultcode');
            $table->index('lastsessionsuccessful');
            $table->index('lastsuccessfuldate');
            $table->index('lastupdatesavailable');
            $table->index('skiplocalcdn');
            $table->index('recommendedupdates');
            $table->index('mrxprotect');
            $table->index('inactiveupdates');
        });
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
