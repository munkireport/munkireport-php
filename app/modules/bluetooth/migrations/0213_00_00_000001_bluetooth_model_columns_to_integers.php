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

    public function up() {
        $legacyVersion = $this->getLegacyModelSchemaVersion('bluetooth');
        $capsule = new Capsule();

        if ($legacyVersion !== null && $legacyVersion < static::$legacySchemaVersion) {
            //Set status to binary
            $capsule::table('bluetooth')->where('bluetooth_status', '=', 'Bluetooth is on ')
                ->update(['bluetooth_status' => 1]);

            $capsule::table('bluetooth')->where('bluetooth_status', '=', 'Bluetooth is off ')
                ->update(['bluetooth_status' => 0]);

            $capsule::table('bluetooth')->where('bluetooth_status', '=', 'Bluetooth is ')
                ->update(['bluetooth_status' => -1]);

            //Set disconnected to -1
            $capsule::table('bluetooth')->where('keyboard_battery', '=', 'Disconnected ')
                ->update(['keyboard_battery' => -1]);

            $capsule::table('bluetooth')->where('mouse_battery', '=', 'Disconnected ')
                ->update(['mouse_battery' => -1]);

            $capsule::table('bluetooth')->where('trackpad_battery', '=', 'Disconnected ')
                ->update(['trackpad_battery' => -1]);

            // Convert percentages to INTEGER
            $capsule::table('bluetooth')->update([
                'keyboard_battery' => $capsule::raw("REPLACE(keyboard_battery, '% battery life remaining ', '')")
            ]);

            $capsule::table('bluetooth')->update([
                'mouse_battery' => $capsule::raw("REPLACE(mouse_battery, '% battery life remaining ', '')")
            ]);

            $capsule::table('bluetooth')->update([
                'trackpad_battery' => $capsule::raw("REPLACE(trackpad_battery, '% battery life remaining ', '')")
            ]);

            $capsule::schema()->table('bluetooth', function(Blueprint $table) {
                $table->integer('bluetooth_status')->change();
                $table->integer('keyboard_battery')->change();
                $table->integer('mouse_battery')->change();
                $table->integer('trackpad_battery')->change();
            });

            $this->markLegacyMigrationRan();
        }
    }
//
//    public function down() {
//        $legacyVersion = $this->getLegacyModelSchemaVersion('bluetooth');
//
//    }
}