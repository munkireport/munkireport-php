<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class NetworkAddDns extends Migration
{
    private $tableName = 'network';

    public function up()
    {
        $capsule = new Capsule();
        
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('ipv4dns')->nullable();
            $table->string('vlans')->nullable();
            $table->integer('activemtu')->nullable();
            $table->string('validmturange')->nullable();
            $table->string('currentmedia')->nullable();
            $table->string('activemedia')->nullable();
            $table->string('searchdomain')->nullable();
        });

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->index('ipv4dns');
            $table->index('vlans');
            $table->index('activemtu');
            $table->index('validmturange');
            $table->index('currentmedia');
            $table->index('activemedia');
            $table->index('searchdomain');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
			$table->dropColumn('ipv4dns');
			$table->dropColumn('vlans');
			$table->dropColumn('activemtu');
			$table->dropColumn('validmturange');
			$table->dropColumn('currentmedia');
			$table->dropColumn('activemedia');
			$table->dropColumn('searchdomain');
        });
    }
}
