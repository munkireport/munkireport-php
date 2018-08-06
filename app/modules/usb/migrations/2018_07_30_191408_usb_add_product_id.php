<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class UsbAddProductId extends Migration
{
    private $tableName = 'usb';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
          $table->string('product_id')->nullable();

          $table->index('product_id', 'product_id_index');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
          $table->dropColumn('product_id');
          
          $table->dropIndex('product_id_index');
        });
    }
}
