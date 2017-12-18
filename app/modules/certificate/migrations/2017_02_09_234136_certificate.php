<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Certificate extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('certificate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->integer('cert_exp_time');
            $table->string('cert_path');
            $table->string('cert_cn');
            $table->string('issuer');
            $table->string('cert_location');
            $table->integer('timestamp')->nullable();
//            $table->timestamps();

            $table->index('cert_cn', 'certificate_cert_cn');
            $table->index('cert_exp_time', 'certificate_cert_exp_time');
            $table->index('cert_location', 'certificate_cert_location');
            $table->index('cert_path', 'certificate_cert_path');
            $table->index('issuer', 'certificate_issuer');
            $table->index('serial_number', 'certificate_serial_number');
            $table->index('timestamp', 'certificate_timestamp');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('certificate');
    }
}
