<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class DirectoryServiceAddBoundColumn extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table('directoryservice', function (Blueprint $table) {
        
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
