<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CLASSNAME extends Migration
{
    private $tableName = 'MODULE';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
          // Example values, please visit https://laravel.com/docs/5.5/migrations#modifying-columns
            $table->string('example_string', 50)->change();
            $table->integer('example_integer')->nullable()->change();

            $table->index('example_string');
            $table->index('example_integer');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('example_string', 100)->change();
            $table->integer('example_integer')->change();

            $table->dropIndex('example_string_index');
            $table->dropIndex('example_integer_index');
        });
    }
}
