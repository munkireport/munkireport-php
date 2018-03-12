<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CertificateNullColumns extends Migration
{
    private $tableName = 'certificate';

    public function up()
    {
        $capsule = new Capsule();
        
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
              $table->bigInteger('cert_exp_time')->nullable();
              $table->string('cert_path')->nullable();
              $table->string('cert_cn')->nullable();
              $table->string('issuer')->nullable();
              $table->string('cert_location')->nullable();
        });
    }

    public function down()
    {
        
    }
}
