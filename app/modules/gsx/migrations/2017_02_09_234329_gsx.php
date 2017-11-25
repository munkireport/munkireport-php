<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Gsx extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('gsx', function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number')->unique();
            $table->string('warrantystatus');
            $table->string('coverageenddate');
            $table->string('coveragestartdate');
            $table->integer('daysremaining');
            $table->string('estimatedpurchasedate');
            $table->string('purchasecountry');
            $table->string('registrationdate');
            $table->string('productdescription');
            $table->string('configdescription');
            $table->string('contractcoverageenddate');
            $table->string('contractcoveragestartdate');
            $table->string('contracttype');
            $table->string('laborcovered');
            $table->string('partcovered');
            $table->string('warrantyreferenceno');
            $table->string('isloaner');
            $table->string('warrantymod');
            $table->string('isvintage');
            $table->string('isobsolete');

            $table->index('configdescription', 'gsx_configdescription');
            $table->index('coverageenddate', 'gsx_coverageenddate');
            $table->index('daysremaining', 'gsx_daysremaining');
            $table->index('estimatedpurchasedate', 'gsx_estimatedpurchasedate');
            $table->index('isvintage', 'gsx_isvintage');
            $table->index('warrantystatus', 'gsx_warrantystatus');
//            $table->timestamps();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('gsx');
    }
}
