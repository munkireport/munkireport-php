<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Extensions extends Migration
{    
    private $tableName = 'extensions';

    public function up()
    {
    
		$capsule = new Capsule();

        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
			$table->string('teamid')->after('codesign');
			$table->renameColumn('codesign', 'developer');						
        });
    }

    public function down()
    {

		$capsule = new Capsule();

        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
			$table->dropColumn('teamid');
 			$table->renameColumn('developer', 'codesign');						
       });
    }
}
