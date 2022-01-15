<?php


namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;

class SystemInformationController
{
    public function php()
    {
        $general = phpinfo(INFO_GENERAL);

        return response()->json([
            'general' => $general,
        ]);
    }

    /**
     * Get information about the configured database adapter and some basic health indicators.
     *
     */
    public function database()
    {
        $connection = DB::connection(DB::getDefaultConnection());
        $pdo = DB::getPdo();

        $response = [
            'database_name' => $connection->getDatabaseName(),
            'server_version' => $pdo->getServerVersion(),
            'client_version' => $pdo->getAttribute(\PDO::ATTR_CLIENT_VERSION),
            'driver_name' => $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME),
//            'server_info' => $pdo->getAttribute(\PDO::ATTR_SERVER_INFO),
        ];

        return response()->json($response);

    }
}
