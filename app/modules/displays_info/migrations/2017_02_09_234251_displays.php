<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Displays extends Migration
{
    private $tableName = 'displays';
    private $tableNameV2 = 'displays_orig';

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

            $table->string('serial_number')->nullable();
            $table->integer('type')->nullable();
            $table->string('display_serial')->nullable();
            $table->string('vendor')->nullable();
            $table->string('model')->nullable();
            $table->string('manufactured')->nullable();
            $table->string('native')->nullable();
            $table->bigInteger('timestamp')->nullable();

            $table->index('serial_number');
            $table->index('display_serial');
            $table->index('vendor');
            $table->index('model');
            $table->index('native');
            $table->index('timestamp');
        });

        if ($migrateData) {
            $capsule::select("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                type,
                display_serial,
                vendor,
                model,
                manufactured,
                native,
                timestamp
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
