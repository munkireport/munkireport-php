<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Sophos extends Migration
{

    public function up()
	{
		$capsule = new Capsule();
		$capsule::schema()->create('sophos', function (Blueprint $table) {
        $table->increments('id');
		$table->string('serial_number')->unique();
		$table->string('installed')->nullable();
        $table->string('running')->nullable();
		$table->string('product_version')->nullable();
		$table->string('engine_version')->nullable();
		$table->string('virus_data_version')->nullable();
		$table->string('user_interface_version')->nullable();

		$table->index('serial_number');
		$table->index('installed');
		$table->index('running');
		$table->index('product_version');
		$table->index('engine_version');
		$table->index('virus_data_version');
		$table->index('user_interface_version');

	});
	}

	public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('sophos');
	}
}	
		
