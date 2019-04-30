<?php
namespace munkireport\lib;
use Illuminate\Database\Capsule\Manager as Capsule;

trait LegacyMigrationSupport
{
    protected $capsule;

    protected function connectDB()
    {
        if(!$this->capsule){
            $this->capsule = new Capsule;

            if( ! $connection = conf('connection')){
                die('Database connection not configured');
            }

            if(has_mysql_db($connection)){
              add_mysql_opts($connection);
            }

            $this->capsule->addConnection($connection);
            $this->capsule->setAsGlobal();
            $this->capsule->bootEloquent();
        }

        return $this->capsule;
    }

    /**
     * Query the `migration` table to retrieve the current model version for a MunkiReport PHP v2 model.
     *
     * @param $tableName string Name of the v2 table to check migrations for.
     *
     * @return integer The current version of the legacy table, or null if no such migration exists (model is either
     *  older than v2 migrations or newer than v3)
     */
    function getLegacyModelSchemaVersion($tableName) {
        $capsule = $this->connectDB();
        if (!$capsule::schema()->hasTable('migration')) return null;

        $currentVersion = $capsule::table('migration')
            ->where('table_name', '=', $tableName)
            ->first();

        if ($currentVersion) {
            return $currentVersion->version;
        } else {
            return null;
        }
    }

    function setLegacyModelSchemaVersion($tableName, $version) {
        $capsule = $this->connectDB();
        $capsule::table('migration')
            ->where('table_name', $tableName)
            ->update(['version' => $version]);
    }

    function markLegacyMigrationRan() {
        $capsule = $this->connectDB();
        $capsule::table('migration')
            ->where('table_name', static::$legacyTableName)
            ->update(['version' => static::$legacySchemaVersion]);
    }

    function markLegacyRollbackRan() {
        $capsule = $this->connectDB();
        $capsule::table('migration')
            ->where('table_name', static::$legacyTableName)
            ->update(['version' => static::$legacySchemaVersion - 1]);
    }
}
