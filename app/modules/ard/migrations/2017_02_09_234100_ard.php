<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Ard extends Migration
{
    private $tableName = 'ard';
    private $tableNameV2 = 'ard_orig';

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
            $table->string('text1');
            $table->string('text2');
            $table->string('text3');
            $table->string('text4');

            $table->index('text1');
            $table->index('text2');
            $table->index('text3');
            $table->index('text4');
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
