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

            $table->string('serial_number')->unique()->nullable();
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
