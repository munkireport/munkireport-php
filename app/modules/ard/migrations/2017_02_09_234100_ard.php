<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Ard extends Migration
{
    private $tableName = 'ard';
    private $tableNameV2 = 'ard_v2';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }

        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('Text1');
            $table->string('Text2');
            $table->string('Text3');
            $table->string('Text4');

            $table->index('Text1');
            $table->index('Text2');
            $table->index('Text3');
            $table->index('Text4');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                Text1,
                Text2,
                Text3,
                Text4
            FROM
                $this->tableNameV2");
        }
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
