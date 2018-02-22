<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Memory extends Migration
{
    private $tableName = 'memory';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name')->nullable();
            $table->string('dimm_size')->nullable();
            $table->string('dimm_speed')->nullable();
            $table->string('dimm_type')->nullable();
            $table->string('dimm_status')->nullable();
            $table->string('dimm_manufacturer')->nullable();
            $table->string('dimm_part_number')->nullable();
            $table->string('dimm_serial_number')->nullable();
            $table->string('dimm_ecc_errors')->nullable();
            $table->integer('global_ecc_state')->nullable();
            $table->boolean('is_memory_upgradeable')->nullable();
            $table->bigInteger('free')->nullable();
            $table->bigInteger('active')->nullable();
            $table->bigInteger('inactive')->nullable();
            $table->bigInteger('speculative')->nullable();
            $table->bigInteger('throttled')->nullable();
            $table->bigInteger('wireddown')->nullable();
            $table->bigInteger('purgeable')->nullable();
            $table->bigInteger('translationfaults')->nullable();
            $table->bigInteger('copyonwrite')->nullable();
            $table->bigInteger('zerofilled')->nullable();
            $table->bigInteger('reactivated')->nullable();
            $table->bigInteger('purged')->nullable();
            $table->bigInteger('filebacked')->nullable();
            $table->bigInteger('anonymous')->nullable();
            $table->bigInteger('storedincompressor')->nullable();
            $table->bigInteger('occupiedbycompressor')->nullable();
            $table->bigInteger('decompressions')->nullable();
            $table->bigInteger('compressions')->nullable();
            $table->bigInteger('pageins')->nullable();
            $table->bigInteger('pageouts')->nullable();
            $table->bigInteger('swapins')->nullable();
            $table->bigInteger('swapouts')->nullable();
            $table->bigInteger('memorypressure')->nullable();
            $table->bigInteger('swaptotal')->nullable();
            $table->bigInteger('swapused')->nullable();
            $table->bigInteger('swapfree')->nullable();
            $table->boolean('swapencrypted')->nullable();
            
            $table->index('serial_number');
            $table->index('name');
            $table->index('dimm_size');
            $table->index('dimm_speed');
            $table->index('dimm_type');
            $table->index('dimm_status');
            $table->index('dimm_manufacturer');
            $table->index('dimm_part_number');
            $table->index('dimm_serial_number');
            $table->index('dimm_ecc_errors');
            $table->index('global_ecc_state');
            $table->index('is_memory_upgradeable');
            $table->index('free');
            $table->index('active');
            $table->index('inactive');
            $table->index('speculative');
            $table->index('throttled');
            $table->index('wireddown');
            $table->index('purgeable');
            $table->index('translationfaults');
            $table->index('copyonwrite');
            $table->index('zerofilled');
            $table->index('reactivated');
            $table->index('purged');
            $table->index('filebacked');
            $table->index('anonymous');
            $table->index('storedincompressor');
            $table->index('occupiedbycompressor');
            $table->index('decompressions');
            $table->index('compressions');
            $table->index('pageins');
            $table->index('pageouts');
            $table->index('swapins');
            $table->index('swapouts');
            $table->index('memorypressure');
            $table->index('swaptotal');
            $table->index('swapused');
            $table->index('swapfree');
            $table->index('swapencrypted');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
    }
}
