<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class BusinessUnit extends Migration
{
    private $tableName = 'business_unit';
    private $tableNameV2 = 'business_unit_orig';

    public function up()
    {;
        $migrateData = false;

        if (Schema::hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if (Schema::hasTable($this->tableName)) {
            Schema::rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }

        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unitid');
            $table->string('property');
            $table->string('value');
        });

        if ($migrateData) {
            Schema::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                unitid,
                property,
                value
            FROM
                $this->tableNameV2");
            Schema::drop($this->tableNameV2);
        }

        // (Re)create indexes
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->index('property');
            $table->index('value');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
        if (Schema::hasTable($this->tableNameV2)) {
            Schema::rename($this->tableNameV2, $this->tableName);
        }
    }
}
