<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Softwareupdate extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('softwareupdate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();

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
        $capsule::schema()->dropIfExists('softwareupdate');
    }
}
