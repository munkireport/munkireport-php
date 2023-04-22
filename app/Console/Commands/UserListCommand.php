<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UserListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List current local database users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all(['name', 'email', 'role', 'objectguid', 'updated_at']);

        $this->table(
            ['Name', 'Email', 'Role', 'ObjectGUID', 'Updated'],
            $users->toArray()
        );

        return Command::SUCCESS;
    }
}
