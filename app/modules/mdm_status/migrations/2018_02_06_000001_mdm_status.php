<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MDMStatus extends Migration
{    

    public function up()
    {
    
		$capsule = new Capsule();

        $capsule::schema()->create('mdm_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
			$table->string('mdm_enrolled');
			$table->string('mdm_enrollment_type');

            $table->index('mdm_enrolled');
            $table->index('mdm_enrollment_type');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('mdm_status');
    }


}
