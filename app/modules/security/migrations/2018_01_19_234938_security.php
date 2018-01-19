<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Security extends Migration
{    
    private $tableName = 'security';

    public function up()
    {
    
		$capsule = new Capsule();

        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
			$table->string('ssh_groups')->after('sip');
						
        });
    }

    public function down()
    {

		$capsule = new Capsule();

        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
			$table->dropColumn('ssh_groups');
        });
    }
}
