<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ExtensionsAddTeamid extends Migration
{
    private $tableName = 'extensions';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('teamid')->after('codesign')->default('');
            $table->renameColumn('codesign', 'developer');

            $table->index('teamid');
            $table->index('developer');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('teamid');
            $table->renameColumn('developer', 'codesign');

            $table->index('codesign');
        });
    }
}
