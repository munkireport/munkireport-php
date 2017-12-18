<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SccmStatus extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('sccm_status', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('agent_status')->nullable();
            $table->string('mgmt_point')->nullable();
            $table->string('enrollment_name')->nullable();
            $table->string('enrollment_server')->nullable();
            $table->string('last_checkin')->nullable();
            $table->string('cert_exp')->nullable();

            $table->index('agent_status', 'sccm_status_agent_status');
            $table->index('cert_exp', 'sccm_status_cert_exp');
            $table->index('enrollment_name', 'sccm_status_enrollment_name');
            $table->index('enrollment_server', 'sccm_status_enrollment_server');
            $table->index('last_checkin', 'sccm_status_last_checkin');
            $table->index('mgmt_point', 'sccm_status_mgmt_point');

//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('sccm_status');
    }
}
