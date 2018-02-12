<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SecurityAddSshGroups extends Migration
{
  private $tableName = 'security';

  public function up()
  {
  
  $capsule = new Capsule();

      $capsule::schema()->table($this->tableName, function (Blueprint $table) {
          $table->string('ssh_groups')->after('sip')->default('');
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
