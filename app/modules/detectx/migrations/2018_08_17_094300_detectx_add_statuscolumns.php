<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Detectx extends Migration
{
    public function up()
    {
        $capsule = new DetectxAddStatuscolumns();
        if (!$capsule::schema()->hasColumn('detectx', 'infectionstatus')) {
          $capsule::schema()->table('detectx', function (Blueprint $table) {
              $table->boolean('infectionstatus')->nullable();
              $table->boolean('issuestatus')->nullable();
          });
        }
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table('detectx', function (Blueprint $table) {
          $table->dropColumn('infectionstatus');
          $table->dropColumn('issuestatus');
      });
    }
}
