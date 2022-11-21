<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConfigDebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print the effective (non-sensitive) configuration when all sources are taken into consideration';

    public static $keys = [
        'database.default',
        'database.connections.mysql.host',
        'database.connections.mysql.port',
        'database.connections.mysql.database',
        'database.connections.sqlite.url',
        'database.connections.sqlite.database',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $config = [];
        foreach (self::$keys as $key) {
            $config[] = [$key, config($key)];
        }

        $this->table(['property', 'value'], $config);
        return Command::SUCCESS;
    }
}
