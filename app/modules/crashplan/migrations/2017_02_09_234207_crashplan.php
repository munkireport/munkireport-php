<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Crashplan extends Migration
{
    public function up()
    {
        $capsule = new Capsule();

        $migrateData = false;

        if ($capsule::schema()->hasTable('crashplan_orig')) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable('crashplan')) {
            $capsule::schema()->rename('crashplan', 'crashplan_orig');
            $migrateData = true;
        }

        $capsule::schema()->create('crashplan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();
            $table->string('destination')->nullable();
            $table->bigInteger('last_success')->nullable();
            $table->integer('duration')->nullable();
            $table->bigInteger('last_failure')->nullable();
            $table->string('reason')->nullable();

            $table->index('reason');
            $table->index('serial_number');
        });

        if ($migrateData) {
            $capsule::select('INSERT INTO 
                crashplan
            SELECT
                id,
                serial_number,
                destination,
                last_success,
                duration,
                last_failure,
                reason
            FROM
                crashplan_orig');
        }

    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('crashplan');
        if ($capsule::schema()->hasTable('crashplan_orig')) {
            $capsule::schema()->rename('crashplan_orig', 'crashplan');
        }
    }
}
