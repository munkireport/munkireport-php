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
              $table->bigInteger('cert_exp_time')->nullable()->change();
              $table->string('cert_path')->nullable()->change();
              $table->string('cert_cn')->nullable()->change();
              $table->string('issuer')->nullable()->change();
              $table->string('cert_location')->nullable()->change();
        });
    }

    public function down()
    {
        
    }
}
