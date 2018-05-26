<?php
/**
 * MunkiReport Legacy Migration
 * 2.13
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class BluetoothModelColumnsToIntegers extends Migration {

    use \munkireport\lib\LegacyMigrationSupport;

    public static $legacySchemaVersion = 1;

//    public function up() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('bluetooth');
//
//    }
//
//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('bluetooth');
//
//    }
}