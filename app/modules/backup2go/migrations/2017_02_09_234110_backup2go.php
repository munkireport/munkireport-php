<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Backup2go extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable('backup2go')) {
            $capsule::schema()->rename('backup2go', 'backup2go_orig');
            $migrateData = true;
        }

        $capsule::schema()->create('backup2go', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('backupdate');

            $table->index('backupdate');
        });
        
        if ($migrateData) {
            $capsule::select('INSERT INTO 
                backup2go
            SELECT
                id,
                serial_number,
                backupdate
            FROM
                backup2go_orig');
        }
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('backup2go');
        if ($capsule::schema()->hasTable('backup2go_orig')) {
            $capsule::schema()->rename('backup2go_orig', 'backup2go');
        }
    }
}
