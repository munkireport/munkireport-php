<?php

namespace App;

use Illuminate\Support\Facades\DB;

/**
 * This class provides access to information about the system that MunkiReport is running on.
 *
 * Various methods are extracted from \Compatibility\Kiss\Database AKA munkireport\lib\Database
 */
class SystemInformation
{
    /**
     * Get information about the current database and its configuration.
     * Also returns some runtime information about the database if it is connectable.
     *
     * @return array A hash of information about the database. The keys are i18n codes.
     * @throws \PDOException if an unsupported attribute was retrieved from the database, in general this should be caught by testing.
     */
    public static function getDatabaseInformation(): array
    {
        $connection = DB::connection(config('database.default'));

        $version = $connection->getPdo()?->getAttribute(\PDO::ATTR_SERVER_VERSION);
        $info = $connection->getPdo()?->getAttribute(\PDO::ATTR_SERVER_INFO);
        $status = $connection->getPdo()?->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
        $client = $connection->getPdo()?->getAttribute(\PDO::ATTR_CLIENT_VERSION);

        try {
            $timeout = $connection->getPdo()?->getAttribute(\PDO::ATTR_TIMEOUT);
        } catch (\PDOException $e) {
            $timeout = null;
        }

        $data = [
            'db.driver' => $connection->getDriverName(),
            'database' => $connection->getDatabaseName(),
            'db.prefix' => $connection->getTablePrefix(),
            'version' => $version,
            'db.method' => $status,
            'db.client.version' => $client,
            'db.info' => $info,
            'timeout' => $timeout,
        ];

        switch ($connection->getDriverName()) {
            case 'mysql':
                $size = DB::table('information_schema.tables', 't')
                    ->select(DB::raw('SUM(ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024 ), 2)) AS size'))
                    ->where('table_schema', '=', $connection->getDatabaseName())
                    ->first()->size;
                $data['db.size'] = "{$size} MB";
                break;
            case 'sqlite':
                $pageSize = DB::select('PRAGMA PAGE_SIZE');
                $pageCount = DB::select('PRAGMA PAGE_COUNT');
                break;
        }

        return $data;
    }

    /**
     * Get information about the current PHP runtime and its configuration by parsing the HTML output of phpinfo().
     *
     * This is not very sustainable and some variables may use php functions in future to avoid the HTML output change
     * causing breakage.
     *
     * @return array PHP information scraped from the phpinfo() output buffer.
     */
    public static function getPhpInformation(): array
    {
        ob_start();
        phpinfo(11);
        $raw = ob_get_clean();
        $phpinfo = array('phpinfo' => array());

        // Remove credits
        $nocreds = preg_replace('#<h1>PHP Credits.*#s', '', $raw);
        if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', $nocreds, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (strlen($match[1])) {
                    $phpinfo[$match[1]] = array();
                } elseif (isset($match[3])) {
                    $keys1 = array_keys($phpinfo);
                    $phpinfo[end($keys1)][$match[2]] = isset($match[4]) ? $match[3] . ' ('.$match[4].')' : str_replace(',', ', ', $match[3]);
                } else {
                    $keys1 = array_keys($phpinfo);
                    $phpinfo[end($keys1)][] = trim(strip_tags($match[2]));
                }
            }
        }

        return $phpinfo;
    }

    /**
     * Get information about the current PHP runtime and configuration using runtime function calls instead of
     * HTML parsing. Should be used in place of getPhpInformation()
     *
     * @return array
     */
    public static function getPhpInformationByFunc(): array
    {
        return [
            'php.version' => phpversion(),
            'php.uname' => php_uname(),
            'php.loaded_extensions' => get_loaded_extensions(),
            'php.ini_loaded_file' => php_ini_loaded_file(),
            'php.ini_scanned_files' => php_ini_scanned_files(),
            'php.memory_peak_usage' => memory_get_peak_usage(),
        ];
    }
}
