<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class TimemachineApfsSnapshots extends Migration
{
    private $tableName = 'timemachine';

    public function up()
    {
        $capsule = new Capsule();
    
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->text('apfs_snapshots')->nullable();

        });
     }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
			$table->dropColumn('apfs_snapshots');
        });
    }
}
