<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserUpdateRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-role {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the role of a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $user = User::where('email', $this->argument('email'))->firstOrFail();
            $role = $this->choice(
                'What global role should this user have?',
                ['user', 'admin'],
                0
            );

            if ($user->role === $role) {
                $this->info("The user {$user->email} already has the role `{$role}`, nothing to do");
                return Command::SUCCESS;
            } else {
                $user->role = $role;
                $user->save();
                $this->info("Updated {$user->email} with role `{$role}`");
                return Command::SUCCESS;
            }

        } catch (ModelNotFoundException $e) {
            $this->info('There was no user found with the given email address, try listing available users with the `user:list` command');
            return Command::FAILURE;
        }
    }
}
