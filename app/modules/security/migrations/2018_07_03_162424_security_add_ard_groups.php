<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SecurityAddArdGroups extends Migration
{
  private $tableName = 'security';

  public function up()
  {
  
  $capsule = new Capsule();

      $capsule::schema()->table($this->tableName, function (Blueprint $table) {
          $table->string('ard_groups')->after('ssh_users')->default('')->nullable();

          $table->index('ard_groups');

      });
  }

  public function down()
  {

  $capsule = new Capsule();

      $capsule::schema()->table($this->tableName, function (Blueprint $table) {
          $table->dropColumn('ard_groups');
      });
  }
}
