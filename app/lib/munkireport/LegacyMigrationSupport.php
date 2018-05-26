<?php
namespace munkireport\lib;
use Illuminate\Database\Capsule\Manager as Capsule;

trait LegacyMigrationSupport
{
    /**
     * Query the `migration` table to retrieve the current model version for a MunkiReport PHP v2 model.
     *
     * @param $tableName string Name of the v2 table to check migrations for.
     *
     * @return integer The current version of the legacy table, or null if no such migration exists (model is either
     *  older than v2 migrations or newer than v3)
     */
    function getLegacyModelSchemaVersion($tableName) {
        $capsule = new Capsule();
        $currentVersion = $capsule::table('migration')
            ->select('version')->where('table_name', $tableName)
            ->first();

        return $currentVersion;
    }

    function setLegacyModelSchemaVersion($tableName, $version) {
        $capsule = new Capsule();
        $capsule::table('migration')
            ->where('table_name', $tableName)
            ->update(['version' => $version]);
    }

    function markLegacyMigrationRan() {
        $capsule = new Capsule();
        $capsule::table('migration')
            ->where('table_name', static::$legacyTableName)
            ->update(['version' => static::$legacySchemaVersion]);
    }

    function markLegacyRollbackRan() {
        $capsule = new Capsule();
        $capsule::table('migration')
            ->where('table_name', static::$legacyTableName)
            ->update(['version' => static::$legacySchemaVersion - 1]);
    }
}