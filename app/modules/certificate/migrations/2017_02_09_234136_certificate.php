<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Certificate extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable('certificate_orig')) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable('certificate')) {
            $capsule::schema()->rename('certificate', 'certificate_orig');
            $migrateData = true;
        }

        $capsule::schema()->create('certificate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->bigInteger('cert_exp_time');
            $table->string('cert_path');
            $table->string('cert_cn');
            $table->string('issuer');
            $table->string('cert_location');

            $table->index('cert_cn');
            $table->index('cert_exp_time');
            $table->index('cert_location');
            $table->index('cert_path');
            $table->index('issuer');
            $table->index('serial_number');
        });

        if ($migrateData) {
            $capsule::select('INSERT INTO 
                certificate
            SELECT
                id,
                serial_number,
                cert_exp_time,
                cert_path,
                cert_cn,
                issuer,
                cert_location
            FROM
                certificate_orig');
        }
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('certificate');
        if ($capsule::schema()->hasTable('certificate_orig')) {
            $capsule::schema()->rename('certificate_orig', 'certificate');
        }
    }
}
