<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;


class Security extends Migration
{
    private $tableName = 'security';
	
    public function up()
    {
		Schema::table($tableName, function($table) {
			$table->string('ssh_groups')->after('sip');
        }
    }

    public function down()
    {
		Schema::table($tableName, function($table) {
			$table->dropColumn($columnName);
        }
    }
}
