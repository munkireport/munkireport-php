<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the password of a local database user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $user = User::where('email', $this->argument('email'))->firstOrFail();

            $password = $this->secret('Password?');
            $confirm = $this->secret('Confirm Password?');

            if ($password !== $confirm) {
                $this->info('Passwords did not match, try again');
                return Command::FAILURE;
            }

            $user->password = bcrypt($password);
            $user->save();
            $this->info('The password has been updated');

            return Command::SUCCESS;
        } catch (ModelNotFoundException $e) {
            $this->info('There was no user found with the given email address, try listing available users with the `user:list` command');
            return Command::FAILURE;
        }
    }
}
