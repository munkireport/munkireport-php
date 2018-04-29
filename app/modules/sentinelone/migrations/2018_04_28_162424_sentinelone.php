<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Sentinelone extends Migration
{
	public function up()
	{ 
		$capsule = new Capsule();
		$capsule::schema()->create('sentinelone', function (Blueprint $table) {
			$table->increments('id');
			$table->string('serial_number');
			$table->boolean('active-threats-present')->nullable();
			$table->string('agent-id')->nullable();
			$table->string('agent-install-time')->nullable();
			$table->boolean('agent-running')->nullable();
			$table->string('agent-version')->nullable();
			$table->boolean('enforcing-security')->nullable();
			$table->string('last-seen')->nullable();
			$table->string('mgmt-url')->nullable();
			$table->boolean('self-protection-enabled')->nullable();

            $table->index('serial_number');
			$table->index('active-threats-present');
			$table->index('agent-id');
			$table->index('agent-install-time');
			$table->index('agent-running');
			$table->index('agent-version');
			$table->index('enforcing-security');
			$table->index('last-seen');
			$table->index('mgmt-url');
			$table->index('self-protection-enabled');

		  });
	}

  public function down()
  {

  $capsule = new Capsule();

        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('sentinelone');
      });
  }
}
