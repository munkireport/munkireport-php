<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class DirectoryServiceAddBoundColumn extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
<<<<<<< HEAD
        $capsule::schema()->table('directoryservice', function (Blueprint $table) {
=======
        $capsule::schema()->create('directoryservice', function (Blueprint $table) {
>>>>>>> 7946aa8365e74e6431b5a8719f5a160fd3f15621
        
            $table->string('bound');            
            $table->index('bound');
        });
    }
    
  public function down()
  {

        $capsule = new Capsule();
        $capsule::schema()->table('directoryservice', function (Blueprint $table) {
        
            $table->dropColumn('bound');
      });
  }
}
