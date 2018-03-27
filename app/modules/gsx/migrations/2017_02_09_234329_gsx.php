<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Gsx extends Migration
{
    private $tableName = 'gsx';
    private $tableNameV2 = 'gsx_orig';

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
            $table->string('serial_number');
            $table->string('warrantystatus')->nullable();
            $table->string('coverageenddate')->nullable();
            $table->string('coveragestartdate')->nullable();
            $table->integer('daysremaining')->nullable();
            $table->string('estimatedpurchasedate')->nullable();
            $table->string('purchasecountry')->nullable();
            $table->string('registrationdate')->nullable();
            $table->string('productdescription')->nullable();
            $table->string('configdescription')->nullable();
            $table->string('contractcoverageenddate')->nullable();
            $table->string('contractcoveragestartdate')->nullable();
            $table->string('contracttype')->nullable();
            $table->string('laborcovered')->nullable();
            $table->string('partcovered')->nullable();
            $table->string('warrantyreferenceno')->nullable();
            $table->string('isloaner')->nullable();
            $table->string('warrantymod')->nullable();
            $table->string('isvintage')->nullable();
            $table->string('isobsolete')->nullable();
        });
        
        if ($migrateData) {
            $capsule::unprepared("INSERT INTO 
                $this->tableName
            SELECT
                id,
                serial_number,
                warrantystatus,
                coverageenddate,
                coveragestartdate,
                daysremaining,
                estimatedpurchasedate,
                purchasecountry,
                registrationdate,
                productdescription,
                configdescription,
                contractcoverageenddate,
                contractcoveragestartdate,
                contracttype,
                laborcovered,
                partcovered,
                warrantyreferenceno,
                isloaner,
                warrantymod,
                isvintage,
                isobsolete
            FROM
                $this->tableNameV2");
            $capsule::schema()->drop($this->tableNameV2);
        }

        // (Re)create indexes
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->unique('serial_number');
            $table->index('configdescription');
            $table->index('coverageenddate');
            $table->index('daysremaining');
            $table->index('estimatedpurchasedate');
            $table->index('isvintage');
            $table->index('warrantystatus');
        });
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
