<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Deploystudio extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('deploystudio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('architecture')->nullable();
            $table->string('cn')->nullable();
            $table->string('dstudio_host_new_network_location')->nullable();
            $table->string('dstudio_host_primary_key')->nullable();
            $table->string('dstudio_host_serial_number')->nullable();
            $table->string('dstudio_host_type')->nullable();
            $table->string('dstudio_hostname')->nullable();
            $table->string('dstudio_last_workflow')->nullable();
            $table->string('dstudio_last_workflow_duration')->nullable();
            $table->string('dstudio_last_workflow_execution_date')->nullable();
            $table->string('dstudio_last_workflow_status')->nullable();
            $table->string('dstudio_mac_addr')->nullable();
            $table->string('dstudio_auto_disable')->nullable();
            $table->string('dstudio_auto_reset_workflow')->nullable();
            $table->string('dstudio_auto_started_workflow')->nullable();
            $table->string('dstudio_bootcamp_windows_computer_name')->nullable();
            $table->boolean('dstudio_disabled');
            $table->string('dstudio_group')->nullable();
            $table->string('dstudio_host_ard_field_1')->nullable();
            $table->string('dstudio_host_ard_field_2')->nullable();
            $table->string('dstudio_host_ard_field_3')->nullable();
            $table->string('dstudio_host_ard_field_4')->nullable();
            $table->string('dstudio_host_ard_ignore_empty_fields')->nullable();
            $table->string('dstudio_host_delete_other_locations')->nullable();
            $table->string('dstudio_host_model_identifier')->nullable();

            $table->index('cn');
            $table->index('dstudio_host_serial_number');
            $table->index('dstudio_hostname');
            $table->index('dstudio_last_workflow');
            $table->index('dstudio_mac_addr');

//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('deploystudio');
    }
}
